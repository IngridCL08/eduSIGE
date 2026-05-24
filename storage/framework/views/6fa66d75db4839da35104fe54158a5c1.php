<?php $__env->startSection('title', 'Catálogo de Materias'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Materias</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.materias.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Materia
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <input type="text" name="buscar" value="<?php echo e(request('buscar')); ?>"
               class="form-input w-64" placeholder="Buscar por clave o nombre...">
        <select name="semestre" class="form-select w-40">
            <option value="">— Semestre —</option>
            <?php for($i = 1; $i <= 9; $i++): ?>
            <option value="<?php echo e($i); ?>" <?php echo e(request('semestre') == $i ? 'selected' : ''); ?>>Semestre <?php echo e($i); ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="<?php echo e(route('escolar.materias.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="card p-0">
    <div class="table-wrapper border-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th class="text-center">Créditos</th>
                    <th class="text-center">H. Teoría</th>
                    <th class="text-center">H. Práctica</th>
                    <th class="text-center">Sem. Sugerido</th>
                    <th class="text-center">Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $materias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span class="font-mono font-medium text-navy-800"><?php echo e($m->clave); ?></span></td>
                    <td class="max-w-xs">
                        <p class="font-medium text-carbon-950 truncate"><?php echo e($m->nombre); ?></p>
                    </td>
                    <td class="text-center font-semibold"><?php echo e($m->creditos); ?></td>
                    <td class="text-center text-carbon-600"><?php echo e($m->horas_teoria); ?>h</td>
                    <td class="text-center text-carbon-600"><?php echo e($m->horas_practica); ?>h</td>
                    <td class="text-center">
                        <?php if($m->semestre_sugerido): ?>
                            <span class="badge-info"><?php echo e($m->semestre_sugerido); ?>°</span>
                        <?php else: ?>
                            <span class="text-carbon-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($m->activa): ?>
                            <span class="badge-success">Activa</span>
                        <?php else: ?>
                            <span class="badge-neutral">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('escolar.materias.show', $m)); ?>" class="btn-secondary btn-sm">Ver</a>
                            <a href="<?php echo e(route('escolar.materias.edit', $m)); ?>" class="btn-secondary btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center py-10 text-carbon-400">No hay materias registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4"><?php echo e($materias->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/materias/index.blade.php ENDPATH**/ ?>