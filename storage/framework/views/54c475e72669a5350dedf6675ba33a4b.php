<?php $__env->startSection('title', 'Períodos Escolares'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Períodos Escolares</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.periodos.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Período
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-1 gap-6">

    
    <?php $activo = $periodos->firstWhere('activo', true); ?>
    <?php if($activo): ?>
    <div class="card border-l-4 border-green-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">Período actual en curso</p>
                <h2 class="text-xl font-bold text-carbon-950"><?php echo e($activo->nombre); ?></h2>
                <p class="text-sm text-carbon-500 mt-1">
                    <?php echo e($activo->fecha_inicio->format('d/m/Y')); ?> — <?php echo e($activo->fecha_fin->format('d/m/Y')); ?>

                    &nbsp;·&nbsp; Semana <?php echo e($activo->semana_actual ?? '—'); ?> de <?php echo e($activo->num_semanas); ?>

                </p>
            </div>
            <div class="flex items-center gap-3">
                
                <form method="POST" action="<?php echo e(route('escolar.periodos.calificaciones', $activo)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit"
                            class="<?php echo e($activo->abierto_calificaciones ? 'btn-danger' : 'btn-success'); ?> btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="<?php echo e($activo->abierto_calificaciones
                                       ? 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
                                       : 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z'); ?>"/>
                        </svg>
                        <?php echo e($activo->abierto_calificaciones ? 'Cerrar calificaciones' : 'Abrir calificaciones'); ?>

                    </button>
                </form>
                
                <form method="POST" action="<?php echo e(route('escolar.periodos.semana', $activo)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn-secondary btn-sm">
                        Avanzar semana →
                    </button>
                </form>
            </div>
        </div>

        
        <div class="mt-4">
            <div class="flex justify-between text-xs text-carbon-500 mb-1">
                <span>Avance del semestre</span>
                <span><?php echo e($activo->porcentaje_avance); ?>%</span>
            </div>
            <div class="w-full bg-carbon-100 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full transition-all" style="width: <?php echo e($activo->porcentaje_avance); ?>%"></div>
            </div>
        </div>

        <?php if($activo->abierto_calificaciones): ?>
        <div class="mt-3 flex items-center gap-2 text-green-700 text-xs font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Sistema de calificaciones ABIERTO desde <?php echo e($activo->fecha_apertura_calificaciones?->format('d/m/Y')); ?>

        </div>
        <?php else: ?>
        <div class="mt-3 flex items-center gap-2 text-carbon-500 text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Sistema de calificaciones CERRADO
            <?php if($activo->fecha_cierre_calificaciones): ?>
                — cerrado el <?php echo e($activo->fecha_cierre_calificaciones->format('d/m/Y')); ?>

            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <div class="card p-0">
        <div class="card-header px-6 pt-5 pb-4">
            <h3 class="card-title">Todos los Períodos</h3>
        </div>
        <div class="table-wrapper rounded-t-none border-t-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fechas</th>
                        <th>Semanas</th>
                        <th>Estado</th>
                        <th>Calificaciones</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <p class="font-medium text-carbon-950"><?php echo e($periodo->nombre); ?></p>
                            <p class="text-xs text-carbon-400"><?php echo e($periodo->anio); ?> — Ciclo <?php echo e($periodo->ciclo); ?></p>
                        </td>
                        <td><span class="badge-info badge"><?php echo e($periodo->tipo_nombre); ?></span></td>
                        <td class="text-xs">
                            <?php echo e($periodo->fecha_inicio->format('d/m/Y')); ?><br>
                            <?php echo e($periodo->fecha_fin->format('d/m/Y')); ?>

                        </td>
                        <td>
                            <span class="text-carbon-950 font-medium"><?php echo e($periodo->semana_actual ?? '—'); ?></span>
                            <span class="text-carbon-400">/ <?php echo e($periodo->num_semanas); ?></span>
                        </td>
                        <td><span class="<?php echo e($periodo->estado_color); ?>"><?php echo e(ucfirst($periodo->estado ?? 'planeacion')); ?></span></td>
                        <td>
                            <?php if($periodo->abierto_calificaciones): ?>
                                <span class="badge-success">Abierto</span>
                            <?php else: ?>
                                <span class="badge-neutral">Cerrado</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="<?php echo e(route('escolar.periodos.show', $periodo)); ?>" class="btn-secondary btn-sm">Ver</a>
                                <a href="<?php echo e(route('escolar.periodos.edit', $periodo)); ?>" class="btn-secondary btn-sm">Editar</a>
                                <?php if($periodo->estado !== 'cerrado' && !$periodo->activo): ?>
                                <form method="POST" action="<?php echo e(route('escolar.periodos.activar', $periodo)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn-success btn-sm"
                                            onclick="return confirm('¿Activar este período como el actual?')">
                                        Activar
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-8 text-carbon-400">No hay períodos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4"><?php echo e($periodos->links()); ?></div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/periodos/index.blade.php ENDPATH**/ ?>