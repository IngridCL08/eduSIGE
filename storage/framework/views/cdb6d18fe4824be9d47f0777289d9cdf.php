<?php $__env->startSection('title','Alumnos'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Alumnos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.alumnos.exportar')); ?>" class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar Excel
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar</label>
            <input name="buscar" value="<?php echo e(request('buscar')); ?>" class="form-input"
                   placeholder="Matrícula, nombre, CURP…">
        </div>
        <div>
            <label class="form-label">Carrera</label>
            <select name="carrera_id" class="form-select">
                <option value="">Todas</option>
                <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php echo e(request('carrera_id') == $c->id ? 'selected' : ''); ?>>
                    <?php echo e($c->clave); ?> — <?php echo e($c->nombre); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Estatus</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = ['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>" <?php echo e(request('status') === $k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="<?php echo e(route('escolar.alumnos.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>


<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th>Período ingreso</th>
                    <th>Promedio</th>
                    <th>Créditos</th>
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $alumnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alumno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusClasses = [
                        'activo'          => 'badge-success',
                        'baja_temporal'   => 'badge-warning',
                        'baja_definitiva' => 'badge-danger',
                        'egresado'        => 'badge-info',
                        'titulado'        => 'badge-neutral',
                    ];
                    $statusLabels = [
                        'activo'          => 'Activo',
                        'baja_temporal'   => 'Baja Temporal',
                        'baja_definitiva' => 'Baja Definitiva',
                        'egresado'        => 'Egresado',
                        'titulado'        => 'Titulado',
                    ];
                ?>
                <tr>
                    <td class="font-mono text-xs font-bold"><?php echo e($alumno->matricula); ?></td>
                    <td>
                        <div class="font-medium text-carbon-900"><?php echo e($alumno->aspirante->nombre_completo); ?></div>
                        <div class="text-xs text-carbon-400"><?php echo e($alumno->aspirante->email); ?></div>
                    </td>
                    <td class="text-sm"><?php echo e($alumno->carrera?->clave ?? '—'); ?></td>
                    <td class="text-sm whitespace-nowrap"><?php echo e($alumno->periodoIngreso?->nombre ?? '—'); ?></td>
                    <td class="font-semibold">
                        <?php echo e($alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—'); ?>

                    </td>
                    <td><?php echo e($alumno->creditos_acumulados ?? 0); ?></td>
                    <td>
                        <span class="<?php echo e($statusClasses[$alumno->status] ?? 'badge-neutral'); ?> badge">
                            <?php echo e($statusLabels[$alumno->status] ?? $alumno->status); ?>

                        </span>
                    </td>
                    <td class="text-right">
                        <a href="<?php echo e(route('escolar.alumnos.show', $alumno)); ?>" class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-10 text-carbon-400">
                        No se encontraron alumnos con los filtros aplicados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($alumnos->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($alumnos->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/alumnos/index.blade.php ENDPATH**/ ?>