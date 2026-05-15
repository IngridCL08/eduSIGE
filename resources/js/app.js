import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

// ─── Alpine.js ───────────────────────────────────────────────
window.Alpine = Alpine;

// ─── ApexCharts global ───────────────────────────────────────
window.ApexCharts = ApexCharts;

// ─── Componentes Alpine globales ──────────────────────────────

// Sidebar con estado persistente
Alpine.data('sidebar', () => ({
    open: localStorage.getItem('sidebarOpen') !== 'false',
    toggle() {
        this.open = !this.open;
        localStorage.setItem('sidebarOpen', this.open);
    },
}));

// Modal genérico
Alpine.data('modal', (initialOpen = false) => ({
    open: initialOpen,
    show() { this.open = true; },
    hide() { this.open = false; },
    toggle() { this.open = !this.open; },
}));

// Dropdown
Alpine.data('dropdown', () => ({
    open: false,
    toggle() { this.open = !this.open; },
    close() { this.open = false; },
}));

// Alerta flash auto-dismiss
Alpine.data('flashAlert', (timeout = 4000) => ({
    visible: true,
    init() {
        if (timeout > 0) {
            setTimeout(() => { this.visible = false; }, timeout);
        }
    },
}));

// Confirmación de eliminación
Alpine.data('confirmDelete', (url) => ({
    open: false,
    targetUrl: url,
    show(url) {
        this.targetUrl = url;
        this.open = true;
    },
    confirm() {
        // Crear y enviar formulario DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.targetUrl;

        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = document.querySelector('meta[name="csrf-token"]').content;

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfField);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    },
}));

// Tabla con búsqueda/filtros en cliente
Alpine.data('dataTable', () => ({
    search: '',
    perPage: 15,
    currentPage: 1,
    sortCol: '',
    sortDir: 'asc',
    filterStatus: '',

    setSort(col) {
        if (this.sortCol === col) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortCol = col;
            this.sortDir = 'asc';
        }
    },
}));

// Gráfica de barras genérica
Alpine.data('barChart', (elementId, series, categories, options = {}) => ({
    chart: null,
    init() {
        const defaults = {
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
            },
            series: series,
            xaxis: { categories: categories },
            colors: ['#1E3A5F', '#3B82F6', '#16a34a', '#d97706'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '55%',
                },
            },
            dataLabels: { enabled: false },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4,
            },
            tooltip: {
                theme: 'light',
            },
        };

        this.chart = new ApexCharts(
            document.getElementById(elementId),
            { ...defaults, ...options }
        );
        this.chart.render();
    },
    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    },
}));

// Gráfica de dona
Alpine.data('donutChart', (elementId, series, labels, options = {}) => ({
    chart: null,
    init() {
        const defaults = {
            chart: {
                type: 'donut',
                height: 260,
                fontFamily: 'Inter, sans-serif',
            },
            series: series,
            labels: labels,
            colors: ['#1E3A5F', '#3B82F6', '#16a34a', '#d97706', '#dc2626'],
            legend: {
                position: 'bottom',
                fontFamily: 'Inter, sans-serif',
                fontSize: '13px',
            },
            dataLabels: {
                formatter: (val) => val.toFixed(1) + '%',
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                    },
                },
            },
        };

        this.chart = new ApexCharts(
            document.getElementById(elementId),
            { ...defaults, ...options }
        );
        this.chart.render();
    },
}));

// Gráfica de línea (tendencia temporal)
Alpine.data('lineChart', (elementId, series, categories, options = {}) => ({
    chart: null,
    init() {
        const defaults = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                sparkline: { enabled: false },
            },
            series: series,
            xaxis: { categories: categories },
            colors: ['#1E3A5F', '#3B82F6'],
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.05,
                },
            },
            dataLabels: { enabled: false },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        };

        this.chart = new ApexCharts(
            document.getElementById(elementId),
            { ...defaults, ...options }
        );
        this.chart.render();
    },
}));

// ─── Iniciar Alpine ───────────────────────────────────────────
Alpine.start();

// ─── Helpers globales ─────────────────────────────────────────

// Formatear moneda MXN
window.formatMXN = (amount) =>
    new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);

// Copiar texto al portapapeles
window.copyToClipboard = async (text) => {
    await navigator.clipboard.writeText(text);
};
