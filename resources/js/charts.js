import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';

Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

function getCSSVar(name, fallback) {
    if (typeof getComputedStyle !== 'undefined') {
        var val = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
        return val || fallback;
    }
    return fallback;
}

const BRAND = {
    get purple() { return getCSSVar('--primary', '#5B21FF'); },
    get purpleLight() { return getCSSVar('--primary-light', '#EDE9FE'); },
    get green() { return getCSSVar('--success', '#10B981'); },
    get greenLight() { return getCSSVar('--success-light', '#D1FAE5'); },
    get blue() { return getCSSVar('--info', '#3B82F6'); },
    get blueLight() { return getCSSVar('--info-light', '#EFF6FF'); },
    get orange() { return getCSSVar('--warning', '#F59E0B'); },
    get orangeLight() { return getCSSVar('--warning-light', '#FFFBEB'); },
    get red() { return getCSSVar('--danger', '#EF4444'); },
    get redLight() { return getCSSVar('--danger-light', '#FEF2F2'); },
    get pink() { return '#EC4899'; },
    get pinkLight() { return '#FDF2F8'; },
    get indigo() { return '#6366F1'; },
    get indigoLight() { return '#EEF2FF'; },
    get teal() { return '#14B8A6'; },
    get tealLight() { return '#F0FDFA'; },
    get slate() { return getCSSVar('--text-muted', '#64748B'); },
    get slateLight() { return getCSSVar('--gray-100', '#F1F5F9'); },
};

const CHART_DEFAULTS = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: getCSSVar('--nav-bg', '#0F172A'),
            titleColor: '#FFFFFF',
            bodyColor: '#CBD5E1',
            borderColor: '#334155',
            borderWidth: 1,
            cornerRadius: 8,
            padding: 12,
            titleFont: { weight: '600', size: 13 },
            bodyFont: { size: 12 },
            displayColors: true,
            boxPadding: 4,
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#94A3B8', font: { size: 11 } },
            border: { display: false },
        },
        y: {
            grid: { color: '#F1F5F9' },
            ticks: { color: '#94A3B8', font: { size: 11 } },
            border: { display: false },
        },
    },
};

function mergeOptions(custom = {}) {
    const base = JSON.parse(JSON.stringify(CHART_DEFAULTS));
    if (custom.plugins) {
        base.plugins = { ...base.plugins, ...custom.plugins };
        if (custom.plugins.legend) {
            base.plugins.legend = { ...CHART_DEFAULTS.plugins.legend, ...custom.plugins.legend };
        }
    }
    if (custom.scales) {
        if (custom.scales.x) base.scales.x = { ...base.scales.x, ...custom.scales.x };
        if (custom.scales.y) base.scales.y = { ...base.scales.y, ...custom.scales.y };
    }
    return base;
}

export function createLineChart(canvasId, { labels = [], datasets = [], options = {} } = {}) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const processedDatasets = datasets.map((ds, i) => ({
        label: ds.label || '',
        data: ds.data || [],
        borderColor: ds.color || Object.values(BRAND)[i % 12],
        backgroundColor: ds.backgroundColor || 'transparent',
        borderWidth: ds.borderWidth ?? 2.5,
        pointRadius: ds.pointRadius ?? 3,
        pointHoverRadius: ds.pointHoverRadius ?? 5,
        tension: ds.tension ?? 0.4,
        fill: ds.fill ?? false,
        ...ds,
    }));

    return new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: processedDatasets },
        options: mergeOptions(options),
    });
}

export function createBarChart(canvasId, { labels = [], datasets = [], options = {}, horizontal = false } = {}) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const processedDatasets = datasets.map((ds, i) => ({
        label: ds.label || '',
        data: ds.data || [],
        backgroundColor: ds.backgroundColor || Object.values(BRAND)[i % 12],
        borderColor: ds.borderColor || 'transparent',
        borderWidth: ds.borderWidth ?? 0,
        borderRadius: ds.borderRadius ?? 6,
        maxBarThickness: ds.maxBarThickness ?? 48,
        ...ds,
    }));

    const opts = mergeOptions(options);
    if (horizontal) {
        const temp = opts.scales.x;
        opts.scales.x = opts.scales.y;
        opts.scales.y = temp;
    }

    return new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: processedDatasets },
        options: { ...opts, indexAxis: horizontal ? 'y' : 'x' },
    });
}

export function createDoughnutChart(canvasId, { labels = [], data = [], colors = [], options = {} } = {}) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const defaultColors = [BRAND.purple, BRAND.green, BRAND.blue, BRAND.orange, BRAND.red, BRAND.pink, BRAND.indigo, BRAND.teal];

    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors.length ? colors : defaultColors.slice(0, data.length),
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 12 },
                        color: '#64748B',
                    },
                },
                tooltip: CHART_DEFAULTS.plugins.tooltip,
                ...options.plugins,
            },
            ...options,
        },
    });
}

export function createPieChart(canvasId, { labels = [], data = [], colors = [], options = {} } = {}) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const defaultColors = [BRAND.purple, BRAND.green, BRAND.blue, BRAND.orange, BRAND.red, BRAND.pink, BRAND.indigo, BRAND.teal];

    return new Chart(ctx, {
        type: 'pie',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors.length ? colors : defaultColors.slice(0, data.length),
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 12 },
                        color: '#64748B',
                    },
                },
                tooltip: CHART_DEFAULTS.plugins.tooltip,
                ...options.plugins,
            },
            ...options,
        },
    });
}

export function createAreaChart(canvasId, { labels = [], datasets = [], options = {} } = {}) {
    const processedDatasets = datasets.map((ds, i) => {
        const color = ds.color || Object.values(BRAND)[i % 12];
        return {
            label: ds.label || '',
            data: ds.data || [],
            borderColor: color,
            backgroundColor: ds.backgroundColor || `${color}18`,
            borderWidth: ds.borderWidth ?? 2.5,
            pointRadius: ds.pointRadius ?? 0,
            pointHoverRadius: ds.pointHoverRadius ?? 4,
            tension: ds.tension ?? 0.4,
            fill: true,
            ...ds,
        };
    });

    return createLineChart(canvasId, {
        labels,
        datasets: processedDatasets,
        options,
    });
}

export function createProgressRing(canvasId, { value = 0, max = 100, color = BRAND.purple, size = 120, strokeWidth = 10 } = {}) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const radius = (size - strokeWidth) / 2;
    const circumference = 2 * Math.PI * radius;
    const progress = Math.min(value / max, 1);

    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [value, max - value],
                backgroundColor: [color, '#F1F5F9'],
                borderWidth: 0,
                circumference: 360,
                rotation: -90,
            }],
        },
        options: {
            responsive: false,
            cutout: `${((size - strokeWidth * 2) / size) * 50}%`,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false },
            },
            animation: {
                animateRotate: true,
                duration: 1200,
            },
        },
    });
}

export { BRAND, Chart };
