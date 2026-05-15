<?php $__env->startSection('title','Bitácora del Sistema'); ?>

<?php $__env->startSection('content'); ?>
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
            <label class="form-label">Usuario</label>
            <select name="user_id" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Acción</label>
            <input name="accion" value="<?php echo e(request('accion')); ?>" class="form-input" placeholder="login, crear_aspirante…">
        </div>
        <div>
            <label class="form-label">Fecha</label>
            <input name="fecha" type="date" value="<?php echo e(request('fecha')); ?>" class="form-input">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm">Filtrar</button>
            <a href="<?php echo e(route('admin.bitacora')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-xs text-carbon-500 whitespace-nowrap"><?php echo e($reg->created_at->format('d/m/Y H:i:s')); ?></td>
                    <td class="font-medium text-sm"><?php echo e($reg->user?->name ?? '<em class="text-carbon-400">Sistema</em>'); ?></td>
                    <td><code class="text-xs bg-navy-50 text-navy-800 px-2 py-0.5 rounded"><?php echo e($reg->accion); ?></code></td>
                    <td class="text-sm text-carbon-600 max-w-xs truncate" title="<?php echo e($reg->descripcion); ?>"><?php echo e($reg->descripcion ?? '—'); ?></td>
                    <td class="text-xs text-carbon-400 font-mono"><?php echo e($reg->ip ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-8 text-carbon-400">No hay registros en la bitácora.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($registros->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($registros->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/bitacora/index.blade.php ENDPATH**/ ?>