<?php $__env->startSection('title','Estadísticas de Aspirantes'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Estadísticas — Aspirantes</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php $__currentLoopData = [
        ['Total registrados', $resumen['total_aspirantes'],       'text-carbon-900',  'bg-carbon-100'],
        ['Ficha pagada',      $resumen['con_ficha_pagada'],       'text-green-700',   'bg-green-100'],
        ['Admitidos',         $resumen['admitidos'],              'text-navy-800',    'bg-navy-100'],
        ['No admitidos',      $resumen['no_admitidos'],           'text-red-700',     'bg-red-100'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $textCls, $bgCls]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-xl <?php echo e($bgCls); ?> px-4 py-4">
        <p class="text-3xl font-black <?php echo e($textCls); ?>"><?php echo e(number_format($count)); ?></p>
        <p class="text-xs text-carbon-500 mt-1"><?php echo e($label); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aspirantes por Carrera</h3></div>
        <?php if(count($porCarrera) > 0): ?>
            <div id="chart-carrera"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        <?php endif; ?>
    </div>

    
    <div class="card">
        <div class="card-header"><h3 class="card-title">Distribución por Estatus</h3></div>
        <?php if(count($porEstatus) > 0): ?>
            <div id="chart-estatus"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        <?php endif; ?>
    </div>

    
    <div class="card">
        <div class="card-header"><h3 class="card-title">Distribución por Sexo</h3></div>
        <?php if(array_sum(array_values($porSexo ?? [])) > 0): ?>
            <div id="chart-sexo"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        <?php endif; ?>
    </div>

    
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aspirantes por Período</h3></div>
        <?php if(count($porPeriodo ?? []) > 0): ?>
            <div id="chart-periodo"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        <?php endif; ?>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const colors = ['#1E3A5F','#3B82F6','#16a34a','#d97706','#64748b','#7c3aed','#dc2626'];

    // Por carrera
    const carrera = <?php echo json_encode($porCarrera, 15, 512) ?>;
    if (Object.keys(carrera).length) {
        new ApexCharts(document.getElementById('chart-carrera'), {
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Aspirantes', data: Object.values(carrera) }],
            xaxis: { categories: Object.keys(carrera) },
            colors: ['#1E3A5F'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }

    // Por estatus
    const estatus = <?php echo json_encode($porEstatus, 15, 512) ?>;
    if (Object.keys(estatus).length) {
        new ApexCharts(document.getElementById('chart-estatus'), {
            chart: { type: 'donut', height: 240, fontFamily: 'Inter, sans-serif' },
            series: Object.values(estatus),
            labels: Object.keys(estatus).map(k => k.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase())),
            colors: colors,
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
            plotOptions: { pie: { donut: { size: '60%' } } },
        }).render();
    }

    // Por sexo
    const sexo = <?php echo json_encode($porSexo ?? [], 15, 512) ?>;
    if (Object.values(sexo).reduce((a,b)=>a+b,0) > 0) {
        const sexoLabels = { M: 'Masculino', F: 'Femenino', O: 'Otro' };
        new ApexCharts(document.getElementById('chart-sexo'), {
            chart: { type: 'pie', height: 240, fontFamily: 'Inter, sans-serif' },
            series: Object.values(sexo),
            labels: Object.keys(sexo).map(k => sexoLabels[k] ?? k),
            colors: ['#1E3A5F','#3B82F6','#64748b'],
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
        }).render();
    }

    // Por período
    const periodo = <?php echo json_encode($porPeriodo ?? [], 15, 512) ?>;
    if (Object.keys(periodo).length) {
        new ApexCharts(document.getElementById('chart-periodo'), {
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Aspirantes', data: Object.values(periodo) }],
            xaxis: { categories: Object.keys(periodo) },
            colors: ['#3B82F6'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/estadisticas/aspirantes.blade.php ENDPATH**/ ?>