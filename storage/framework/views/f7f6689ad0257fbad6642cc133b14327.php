<?php $__env->startSection('title','Estadísticas de Alumnos'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Estadísticas — Alumnos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <?php $__currentLoopData = [
        ['Total alumnos',        $resumen['total_alumnos'],     'text-carbon-900', 'bg-carbon-100'],
        ['Activos',              $resumen['activos'],           'text-green-700',  'bg-green-100'],
        ['Bajas',                $resumen['bajas'],             'text-red-700',    'bg-red-100'],
        ['Egresados/Titulados',  $resumen['egresados'],         'text-navy-800',   'bg-navy-100'],
        ['En riesgo (< 7.0)',    $resumen['en_riesgo'],         'text-amber-700',  'bg-amber-100'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $textCls, $bgCls]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-xl <?php echo e($bgCls); ?> px-4 py-4">
        <p class="text-3xl font-black <?php echo e($textCls); ?>"><?php echo e(number_format($count)); ?></p>
        <p class="text-xs text-carbon-500 mt-1"><?php echo e($label); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-card-icon bg-amber-100">
            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <div>
            <p class="stat-card-value text-amber-700">
                <?php echo e($resumen['promedio_general'] ? number_format($resumen['promedio_general'], 2) : '—'); ?>

            </p>
            <p class="stat-card-label">Promedio General</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value"><?php echo e(number_format($resumen['creditos_promedio'] ?? 0, 0)); ?></p>
            <p class="stat-card-label">Créditos Promedio</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value text-green-700">
                <?php echo e($resumen['tasa_acreditacion'] ? number_format($resumen['tasa_acreditacion'], 1) . '%' : '—'); ?>

            </p>
            <p class="stat-card-label">Tasa de Acreditación</p>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="card">
        <div class="card-header"><h3 class="card-title">Alumnos por Carrera</h3></div>
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
        <div class="card-header"><h3 class="card-title">Distribución por Semestre</h3></div>
        <?php if(count($porSemestre ?? []) > 0): ?>
            <div id="chart-semestre"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos de semestre.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Distribución por Género</h3></div>
        <?php if(count($porGenero ?? []) > 0): ?>
            <div id="chart-genero"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos de género.</p>
        <?php endif; ?>
    </div>

    <div class="card lg:col-span-2">
        <div class="card-header"><h3 class="card-title">Promedio por Carrera</h3></div>
        <?php if(count($promedioPorCarrera ?? []) > 0): ?>
            <div id="chart-promedio"></div>
        <?php else: ?>
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos de promedio por carrera.</p>
        <?php endif; ?>
    </div>

</div>


<?php if(count($alumnosEnRiesgo ?? []) > 0): ?>
<div class="card p-0">
    <div class="card-header px-6 pt-5 pb-4">
        <div>
            <h3 class="card-title">Alumnos en Riesgo Académico</h3>
            <p class="text-xs text-carbon-500 mt-1">Promedio general menor a 7.0 — Top 10</p>
        </div>
        <span class="badge-warning"><?php echo e($resumen['en_riesgo']); ?> total</span>
    </div>
    <div class="table-wrapper rounded-t-none border-t-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th class="text-center">Semestre</th>
                    <th class="text-center">Promedio</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $alumnosEnRiesgo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alumno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="font-mono font-medium text-navy-800"><?php echo e($alumno->matricula); ?></td>
                    <td class="font-medium text-carbon-950"><?php echo e($alumno->nombre_completo); ?></td>
                    <td class="text-sm text-carbon-600"><?php echo e($alumno->carrera->clave ?? '—'); ?></td>
                    <td class="text-center"><span class="badge-info"><?php echo e($alumno->semestre_actual); ?>°</span></td>
                    <td class="text-center">
                        <span class="font-bold <?php echo e($alumno->promedio_general < 6.0 ? 'text-red-600' : 'text-amber-600'); ?>">
                            <?php echo e(number_format($alumno->promedio_general, 2)); ?>

                        </span>
                    </td>
                    <td class="text-right">
                        <a href="<?php echo e(route('escolar.alumnos.show', $alumno)); ?>" class="btn-secondary btn-sm">Ver</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const colors = ['#1a0960','#2d1590','#4219bf','#5E2DEE','#7c56ff','#a68bff'];

    const carrera = <?php echo json_encode($porCarrera, 15, 512) ?>;
    if (Object.keys(carrera).length) {
        new ApexCharts(document.getElementById('chart-carrera'), {
            chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Alumnos', data: Object.values(carrera) }],
            xaxis: { categories: Object.keys(carrera) },
            colors: ['#2d1590'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }

    const estatus = <?php echo json_encode($porEstatus, 15, 512) ?>;
    if (Object.keys(estatus).length) {
        const statusLabels = {
            activo: 'Activo', baja_temporal: 'Baja Temporal',
            baja_definitiva: 'Baja Definitiva', egresado: 'Egresado', titulado: 'Titulado'
        };
        new ApexCharts(document.getElementById('chart-estatus'), {
            chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
            series: Object.values(estatus),
            labels: Object.keys(estatus).map(k => statusLabels[k] ?? k),
            colors: colors,
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
            plotOptions: { pie: { donut: { size: '60%' } } },
        }).render();
    }

    const semestre = <?php echo json_encode($porSemestre ?? [], 15, 512) ?>;
    if (Object.keys(semestre).length) {
        new ApexCharts(document.getElementById('chart-semestre'), {
            chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Alumnos', data: Object.values(semestre) }],
            xaxis: { categories: Object.keys(semestre).map(s => `${s}°`) },
            colors: ['#4219bf'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '65%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }

    const genero = <?php echo json_encode($porGenero ?? [], 15, 512) ?>;
    if (Object.keys(genero).length) {
        const genLabels = { M: 'Masculino', F: 'Femenino', masculino: 'Masculino', femenino: 'Femenino' };
        new ApexCharts(document.getElementById('chart-genero'), {
            chart: { type: 'pie', height: 260, fontFamily: 'Inter, sans-serif' },
            series: Object.values(genero),
            labels: Object.keys(genero).map(k => genLabels[k] ?? k),
            colors: ['#2d1590', '#e879f9', '#a68bff'],
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v, opts) => opts.w.globals.series[opts.seriesIndex] + ' (' + v.toFixed(0) + '%)' },
        }).render();
    }

    const promedio = <?php echo json_encode($promedioPorCarrera ?? [], 15, 512) ?>;
    if (Object.keys(promedio).length) {
        new ApexCharts(document.getElementById('chart-promedio'), {
            chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Promedio', data: Object.values(promedio).map(v => parseFloat(v).toFixed(2)) }],
            xaxis: { categories: Object.keys(promedio) },
            yaxis: { min: 0, max: 10, labels: { formatter: (v) => v.toFixed(1) } },
            colors: ['#16a34a'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' }, formatter: (v) => parseFloat(v).toFixed(2) },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/estadisticas/alumnos.blade.php ENDPATH**/ ?>