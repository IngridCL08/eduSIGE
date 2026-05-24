<?php $__env->startSection('title', 'Adeudos'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Adeudos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.adeudos.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Adeudo
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <input type="text" name="buscar" value="<?php echo e(request('buscar')); ?>"
               class="form-input w-64" placeholder="Buscar por matrícula o nombre...">
        <select name="tipo" class="form-select w-48">
            <option value="">— Tipo —</option>
            <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php echo e(request('tipo') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="form-select w-36">
            <option value="">— Estado —</option>
            <option value="pendiente" <?php echo e(request('status') === 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
            <option value="pagado"    <?php echo e(request('status') === 'pagado'    ? 'selected' : ''); ?>>Pagado</option>
            <option value="vencido"   <?php echo e(request('status') === 'vencido'   ? 'selected' : ''); ?>>Vencido</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="<?php echo e(route('escolar.adeudos.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="card p-0">
    <div class="table-wrapper border-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th class="text-right">Monto</th>
                    <th class="text-center">Estado</th>
                    <th>Vencimiento</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $adeudos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adeudo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <p class="font-medium text-carbon-950"><?php echo e($adeudo->alumno->nombre_completo); ?></p>
                        <p class="text-xs text-carbon-400 font-mono"><?php echo e($adeudo->alumno->matricula); ?></p>
                    </td>
                    <td>
                        <?php if($adeudo->tipo): ?>
                            <span class="badge-info badge text-xs"><?php echo e($tipos[$adeudo->tipo] ?? $adeudo->tipo); ?></span>
                        <?php else: ?>
                            <span class="text-carbon-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="max-w-xs">
                        <p class="text-sm text-carbon-700 truncate"><?php echo e($adeudo->concepto); ?></p>
                    </td>
                    <td class="text-right font-medium text-carbon-950">
                        <?php echo e($adeudo->monto ? $adeudo->monto_formateado : '—'); ?>

                    </td>
                    <td class="text-center">
                        <span class="<?php echo e($adeudo->status_color); ?>"><?php echo e($adeudo->status_nombre); ?></span>
                    </td>
                    <td class="text-sm text-carbon-500">
                        <?php echo e($adeudo->fecha_vencimiento?->format('d/m/Y') ?? '—'); ?>

                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('escolar.adeudos.show', $adeudo)); ?>" class="btn-secondary btn-sm">Ver</a>
                            <?php if($adeudo->status === 'pendiente'): ?>
                            <form method="POST" action="<?php echo e(route('escolar.adeudos.liquidar', $adeudo)); ?>"
                                  onsubmit="return confirm('¿Marcar como liquidado?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn-success btn-sm">Liquidar</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center py-10 text-carbon-400">No hay adeudos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4"><?php echo e($adeudos->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/adeudos/index.blade.php ENDPATH**/ ?>