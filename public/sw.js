// Skulbase service worker v3 — safe static-shell caching only.
//
// Guarantees:
//   * Only GET requests ever touch Cache Storage.
//   * Only whitelisted static prefixes (/build/assets/, /icons/,
//     /favicon*, /manifest.json) are read from or written to the cache.
//   * HTML documents, API responses and every authenticated/live route
//     always go straight to the network — they can never be cached,
//     regardless of how application routes evolve.
//   * Vite's hashed asset filenames are discovered from
//     /build/manifest.json at install time, so nothing is hardcoded and
//     future `npm run build` runs keep working unchanged.

const CACHE_NAME = 'skulbase-v3';

// Static shell files precached on install. No server-rendered pages here:
// Blade pages carry per-session CSRF tokens and live data.
const PRECACHE_URLS = [
  '/manifest.json',
  '/icons/icon-192.png',
  '/icons/icon-512.png',
  '/favicon.ico',
  '/favicon-16x16.png',
  '/favicon-32x32.png',
  '/favicon-48x48.png',
];

// The ONLY URL prefixes that may enter Cache Storage. Everything else is
// passed through to the network untouched.
const CACHEABLE_PREFIXES = [
  '/build/assets/',
  '/icons/',
  '/favicon',
  '/manifest.json',
];

// Walk Laravel's Vite manifest and return every built asset URL it lists
// (entry chunks, CSS, imported and dynamic chunks). No hardcoded hashes.
function collectViteAssetUrls(manifest) {
  const urls = [];
  const seen = new Set();

  const visit = (key, depth) => {
    if (!key || seen.has(key) || depth > 5) {
      return;
    }
    seen.add(key);

    const entry = manifest[key];
    if (!entry) {
      return;
    }

    if (typeof entry.file === 'string') {
      urls.push(`/build/${entry.file}`);
    }
    if (Array.isArray(entry.css)) {
      entry.css.forEach((file) => urls.push(`/build/${file}`));
    }
    if (Array.isArray(entry.imports)) {
      entry.imports.forEach((importKey) => visit(importKey, depth + 1));
    }
    if (Array.isArray(entry.dynamicImports)) {
      entry.dynamicImports.forEach((importKey) => visit(importKey, depth + 1));
    }
  };

  Object.keys(manifest).forEach((key) => visit(key, 0));

  return urls;
}

async function precache() {
  const cache = await caches.open(CACHE_NAME);
  const urls = [...PRECACHE_URLS];

  try {
    const response = await fetch('/build/manifest.json', { cache: 'no-store' });
    if (response.ok) {
      const manifest = await response.json();
      urls.push(...collectViteAssetUrls(manifest));
    }
  } catch (error) {
    // No build manifest (dev mode / fresh checkout): shell files only.
  }

  // Add files individually so one missing file cannot abort installation.
  await Promise.all(
    urls.map(async (url) => {
      try {
        await cache.add(url);
      } catch (error) {
        // Ignore individual failures; runtime caching fills gaps on demand.
      }
    })
  );

  // Prune anything cached that is not part of the current build.
  const keep = new Set(urls);
  const keys = await cache.keys();
  await Promise.all(
    keys
      .filter((request) => !keep.has(new URL(request.url).pathname))
      .map((request) => cache.delete(request))
  );
}

self.addEventListener('install', (event) => {
  event.waitUntil(precache());
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_NAME)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const request = event.request;

  // Non-GET requests must never interact with Cache Storage: fall through
  // so the browser handles them normally (cookies, CSRF, mutations).
  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);

  // Cross-origin requests are ignored entirely.
  if (url.origin !== self.location.origin) {
    return;
  }

  // API endpoints are always live — network only, never cached.
  if (url.pathname.startsWith('/api/')) {
    return;
  }

  // Page navigations always hit the network. We deliberately do NOT fall
  // back to cached HTML, so an authenticated page can never go stale and
  // offline shows the browser's normal error for v1.
  if (request.mode === 'navigate') {
    return;
  }

  // Any path outside these safe prefixes (dashboards, students, teachers,
  // parents, fees, attendance, results, report cards, subscriptions,
  // payments, settings, affiliates, ...) falls through to the network.
  // Because only whitelisted paths reach the code below, application data
  // cannot be cached even when new routes appear.
  if (!CACHEABLE_PREFIXES.some((prefix) => url.pathname.startsWith(prefix))) {
    return;
  }

  // Immutable static assets: cache-first, filling the cache on the way
  // back through.
  event.respondWith(
    caches.match(request).then((cached) => {
      if (cached) {
        return cached;
      }
      return fetch(request).then((response) => {
        if (response.ok) {
          const copy = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
        }
        return response;
      });
    })
  );
});
