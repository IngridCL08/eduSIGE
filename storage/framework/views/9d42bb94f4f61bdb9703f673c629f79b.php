<?php $__env->startSection('title','Usuarios'); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Usuario
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Buscar</label>
            <input name="buscar" value="<?php echo e(request('buscar')); ?>" class="form-input" placeholder="Nombre o correo…">
        </div>
        <div>
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($rol->name); ?>" <?php echo e(request('rol') == $rol->name ? 'selected' : ''); ?>>
                        <?php echo e(ucfirst($rol->name)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm">Filtrar</button>
            <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Último acceso</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="font-medium"><?php echo e($usuario->name); ?></td>
                    <td class="text-carbon-500"><?php echo e($usuario->email); ?></td>
                    <td>
                        <span class="badge-navy"><?php echo e($usuario->rolNombre()); ?></span>
                    </td>
                    <td class="text-xs text-carbon-400">
                        <?php echo e($usuario->ultimo_acceso?->diffForHumans() ?? 'Nunca'); ?>

                    </td>
                    <td>
                        <?php if($usuario->activo): ?>
                            <span class="badge-success">Activo</span>
                        <?php else: ?>
                            <span class="badge-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('admin.usuarios.edit', $usuario)); ?>" class="btn-icon btn-sm btn-secondary" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.usuarios.toggle', $usuario)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn-icon btn-sm <?php echo e($usuario->activo ? 'btn-warning' : 'btn-success'); ?>" title="<?php echo e($usuario->activo ? 'Desactivar' : 'Activar'); ?>">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($usuario->activo ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'); ?>"/></svg>
                                </button>
                            </form>
                            <?php if($usuario->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.usuarios.destroy', $usuario)); ?>"
                                  x-data onsubmit="return confirm('¿Eliminar usuario?')">
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
                <tr><td colspan="6" class="text-center py-8 text-carbon-400">No hay usuarios registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($usuarios->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($usuarios->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>