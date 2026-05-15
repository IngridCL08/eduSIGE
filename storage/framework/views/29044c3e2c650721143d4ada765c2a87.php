<?php $__env->startSection('title', 'Dashboard Global'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Dashboard Global</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <?php
        $kpis = [
            ['label' => 'Usuarios',        'value' => $stats['total_usuarios'],   'color' => 'bg-navy-100 text-navy-800'],
            ['label' => 'Usuarios Activos','value' => $stats['usuarios_activos'],  'color' => 'bg-green-100 text-green-700'],
            ['label' => 'Aspirantes',      'value' => $stats['total_aspirantes'],  'color' => 'bg-blue-100 text-blue-700'],
            ['label' => 'Alumnos',         'value' => $stats['total_alumnos'],     'color' => 'bg-amber-100 text-amber-700'],
            ['label' => 'Fichas Hoy',      'value' => $stats['fichas_hoy'],        'color' => 'bg-purple-100 text-purple-700'],
            ['label' => 'Ingresos Mes',    'value' => '$' . number_format($stats['ingresos_mes'], 0), 'color' => 'bg-emerald-100 text-emerald-700'],
        ];
        ?>

        <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card text-center py-4">
                <p class="text-2xl font-bold <?php echo e($kpi['color']); ?> rounded-lg py-1 px-2 inline-block mb-1">
                    <?php echo e($kpi['value']); ?>

                </p>
                <p class="text-xs text-carbon-500 mt-1"><?php echo e($kpi['label']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumen Financiero</h3>
                <a href="<?php echo e(route('financiero.dashboard')); ?>" class="text-xs text-navy-500 hover:text-navy-700">
                    Ir al módulo →
                </a>
            </div>
            <div class="space-y-3">
                <?php
                $fiFilas = [
                    ['Fichas pagadas',   $resumenFinanciero['pagadas'],    'badge-success'],
                    ['Fichas pendientes',$resumenFinanciero['pendientes'],  'badge-warning'],
                    ['Fichas vencidas',  $resumenFinanciero['vencidas'],    'badge-danger'],
                    ['Total ingresos',   '$' . number_format($resumenFinanciero['ingresos_total'], 2), 'badge-navy'],
                ];
                ?>
                <?php $__currentLoopData = $fiFilas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $valor, $badge]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                        <span class="text-sm text-carbon-600"><?php echo e($label); ?></span>
                        <span class="<?php echo e($badge); ?>"><?php echo e($valor); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumen Escolar</h3>
                <a href="<?php echo e(route('escolar.dashboard')); ?>" class="text-xs text-navy-500 hover:text-navy-700">
                    Ir al módulo →
                </a>
            </div>
            <div class="space-y-3">
                <?php
                $esFilas = [
                    ['Total aspirantes',  $resumenEscolar['total_aspirantes'],  'badge-navy'],
                    ['Admitidos',         $resumenEscolar['admitidos'],          'badge-success'],
                    ['En proceso',        $resumenEscolar['en_proceso'],         'badge-warning'],
                    ['Alumnos activos',   $resumenEscolar['alumnos_activos'],    'badge-info'],
                    ['Egresados',         $resumenEscolar['egresados'],          'badge-neutral'],
                    ['Titulados',         $resumenEscolar['titulados'],          'badge-navy'],
                ];
                ?>
                <?php $__currentLoopData = $esFilas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $valor, $badge]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                        <span class="text-sm text-carbon-600"><?php echo e($label); ?></span>
                        <span class="<?php echo e($badge); ?>"><?php echo e($valor); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Actividad Reciente del Sistema</h3>
                <a href="<?php echo e(route('admin.bitacora')); ?>" class="text-xs text-navy-500 hover:text-navy-700">
                    Ver bitácora completa →
                </a>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ultimaActividad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-xs text-carbon-500"><?php echo e($reg->created_at->format('d/m H:i')); ?></td>
                                <td><?php echo e($reg->user?->name ?? 'Sistema'); ?></td>
                                <td><code class="text-xs bg-carbon-100 px-1.5 py-0.5 rounded"><?php echo e($reg->accion); ?></code></td>
                                <td class="text-xs text-carbon-400"><?php echo e($reg->ip ?? '—'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-carbon-400 py-4">Sin actividad registrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>