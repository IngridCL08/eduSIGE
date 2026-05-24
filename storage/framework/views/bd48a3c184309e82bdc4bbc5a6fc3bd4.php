<?php $__env->startSection('title','Períodos Escolares'); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.periodos.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Período
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Ciclo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Aspirantes</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($periodo->activo ? 'bg-blue-50/50' : ''); ?>">
                    <td class="font-semibold"><?php echo e($periodo->nombre); ?></td>
                    <td><?php echo e($periodo->anio); ?></td>
                    <td><?php echo e($periodo->ciclo); ?></td>
                    <td><?php echo e($periodo->fecha_inicio->format('d/m/Y')); ?></td>
                    <td><?php echo e($periodo->fecha_fin->format('d/m/Y')); ?></td>
                    <td><?php echo e($periodo->aspirantes_count); ?></td>
                    <td>
                        <?php if($periodo->activo): ?>
                            <span class="badge-success">● Activo</span>
                        <?php else: ?>
                            <span class="badge-neutral">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('admin.periodos.edit', $periodo)); ?>" class="btn-icon btn-sm btn-secondary" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <?php if(!$periodo->activo): ?>
                            <form method="POST" action="<?php echo e(route('admin.periodos.activar', $periodo)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn-primary btn-sm" title="Marcar como activo">Activar</button>
                            </form>
                            <?php endif; ?>
                            <?php if($periodo->aspirantes_count === 0): ?>
                            <form method="POST" action="<?php echo e(route('admin.periodos.destroy', $periodo)); ?>" onsubmit="return confirm('¿Eliminar período?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn-icon btn-sm btn-danger" title="Eliminar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center py-8 text-carbon-400">No hay períodos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($periodos->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($periodos->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/periodos/index.blade.php ENDPATH**/ ?>