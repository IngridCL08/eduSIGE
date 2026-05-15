<?php $__env->startSection('title','Carreras'); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.carreras.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Carrera
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Aspirantes</th>
                    <th>Alumnos</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carrera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><code class="text-xs bg-navy-50 text-navy-800 px-2 py-0.5 rounded font-mono"><?php echo e($carrera->clave); ?></code></td>
                    <td class="font-medium"><?php echo e($carrera->nombre); ?></td>
                    <td><?php echo e($carrera->aspirantes_count); ?></td>
                    <td><?php echo e($carrera->alumnos_count); ?></td>
                    <td>
                        <?php if($carrera->activa): ?>
                            <span class="badge-success">Activa</span>
                        <?php else: ?>
                            <span class="badge-neutral">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('admin.carreras.edit', $carrera)); ?>" class="btn-icon btn-sm btn-secondary" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.carreras.toggle', $carrera)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn-icon btn-sm <?php echo e($carrera->activa ? 'btn-warning' : 'btn-success'); ?>" title="<?php echo e($carrera->activa ? 'Desactivar' : 'Activar'); ?>">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($carrera->activa ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'); ?>"/></svg>
                                </button>
                            </form>
                            <?php if($carrera->aspirantes_count === 0 && $carrera->alumnos_count === 0): ?>
                            <form method="POST" action="<?php echo e(route('admin.carreras.destroy', $carrera)); ?>" onsubmit="return confirm('¿Eliminar carrera?')">
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
                <tr><td colspan="6" class="text-center py-8 text-carbon-400">No hay carreras registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($carreras->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($carreras->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/carreras/index.blade.php ENDPATH**/ ?>