<?php $__env->startSection('title','Reporte de Ingresos'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Reporte de Ingresos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('financiero.reportes.pdf', array_merge(request()->all(), ['tipo'=>'ingresos']))); ?>"
       target="_blank" class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar PDF
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="form-label">Período</label>
            <select name="periodo_id" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('periodo_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Método de pago</label>
            <select name="metodo_pago" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = ['conekta'=>'Conekta','paypal'=>'PayPal','transferencia'=>'Transferencia','efectivo'=>'Efectivo','otro'=>'Otro']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>" <?php echo e(request('metodo_pago') === $k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Desde</label>
            <input type="date" name="desde" value="<?php echo e(request('desde')); ?>" class="form-input">
        </div>
        <div>
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" value="<?php echo e(request('hasta')); ?>" class="form-input">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="<?php echo e(route('financiero.reportes.ingresos')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div>
            <p class="stat-card-value">$<?php echo e(number_format($totales['total_ingresos'], 2)); ?></p>
            <p class="stat-card-label">Total Ingresos</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value"><?php echo e(number_format($totales['total_fichas'])); ?></p>
            <p class="stat-card-label">Fichas Pagadas</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value">$<?php echo e(number_format($totales['promedio_monto'], 2)); ?></p>
            <p class="stat-card-label">Monto Promedio</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value"><?php echo e($totales['mes_mayor'] ?? '—'); ?></p>
            <p class="stat-card-label">Mes de Mayor Ingreso</p>
        </div>
    </div>
</div>


<div class="card mb-5">
    <div class="card-header">
        <h3 class="card-title">Ingresos por Mes</h3>
    </div>
    <div id="chart-ingresos-reporte"></div>
</div>


<div class="card p-0 overflow-hidden">
    <div class="px-4 py-3 border-b border-carbon-100 flex items-center justify-between">
        <h3 class="font-semibold text-carbon-900">Fichas Pagadas</h3>
        <a href="<?php echo e(route('financiero.reportes.exportar', array_merge(request()->all(), ['tipo'=>'fichas']))); ?>"
           class="btn-secondary btn-sm">Exportar Excel</a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Fecha Pago</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $fichas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ficha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="font-mono text-xs"><?php echo e($ficha->folio_ficha); ?></td>
                    <td>
                        <div class="font-medium text-sm"><?php echo e($ficha->aspirante->nombre_completo); ?></div>
                        <div class="text-xs text-carbon-400 font-mono"><?php echo e($ficha->aspirante->folio); ?></div>
                    </td>
                    <td class="text-sm"><?php echo e($ficha->aspirante->carrera?->clave ?? '—'); ?></td>
                    <td class="text-sm"><?php echo e($ficha->aspirante->periodo?->nombre ?? '—'); ?></td>
                    <td class="font-semibold"><?php echo e($ficha->monto_formateado); ?></td>
                    <td class="text-sm"><?php echo e($ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—'); ?></td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap"><?php echo e($ficha->fecha_pago?->format('d/m/Y H:i') ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-10 text-carbon-400">
                        No hay fichas pagadas con los filtros seleccionados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($fichas->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($fichas->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = <?php echo json_encode($ingresosMes, 15, 512) ?>;
    new ApexCharts(document.getElementById('chart-ingresos-reporte'), {
        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        series: [{ name: 'Ingresos MXN', data: data.totales }],
        xaxis: { categories: data.meses },
        colors: ['#1E3A5F'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        yaxis: { labels: { formatter: (v) => '$' + v.toLocaleString('es-MX') } },
        tooltip: { y: { formatter: (v) => '$' + v.toLocaleString('es-MX', { minimumFractionDigits: 2 }) } },
    }).render();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/reportes/ingresos.blade.php ENDPATH**/ ?>