{{-- ================================================================
     FOOTER PARTIAL — Application Footer
     ================================================================ --}}

<footer class="sb-footer">
    <span class="sb-footer-copyright">&copy; {{ date('Y') }} {{ Auth::user()->school->name ?? 'Skulbase' }}. All Rights Reserved.</span>
    <span class="sb-footer-divider">&middot;</span>
    <span class="sb-footer-credit">Powered by <strong>Skulbase</strong> &middot; Designed &amp; Developed by Mubarak Lawal</span>
</footer>
