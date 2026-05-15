<?php $__env->startSection('title', 'Aspirantes'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Aspirantes</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.aspirantes.exportar', request()->only(['periodo_id','carrera_id','status']))); ?>"
       class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Excel
    </a>
    <a href="<?php echo e(route('escolar.aspirantes.create')); ?>" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Aspirante
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <form method="GET" class="card mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input name="buscar" value="<?php echo e(request('buscar')); ?>" type="text"
                       class="form-input" placeholder="Folio, nombre, CURP, email…">
            </div>
            <div>
                <label class="form-label">Período</label>
                <select name="periodo_id" class="form-select">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>" <?php echo e(request('periodo_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Carrera</label>
                <select name="carrera_id" class="form-select">
                    <option value="">Todas</option>
                    <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(request('carrera_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Estatus</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(request('status') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Sexo</label>
                <select name="sexo" class="form-select">
                    <option value="">Todos</option>
                    <option value="M" <?php echo e(request('sexo') == 'M' ? 'selected' : ''); ?>>Masculino</option>
                    <option value="F" <?php echo e(request('sexo') == 'F' ? 'selected' : ''); ?>>Femenino</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <a href="<?php echo e(route('escolar.aspirantes.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
            <button type="submit" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filtrar
            </button>
        </div>
    </form>

    
    <div class="card p-0 overflow-hidden">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Período</th>
                        <th>Estatus</th>
                        <th>Registro</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $aspirantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aspirante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="font-mono text-sm font-medium"><?php echo e($aspirante->folio); ?></td>
                            <td>
                                <div class="font-medium text-carbon-900"><?php echo e($aspirante->nombre_completo); ?></div>
                                <div class="text-xs text-carbon-400"><?php echo e($aspirante->email); ?></div>
                            </td>
                            <td class="max-w-[180px]">
                                <span class="text-sm truncate block" title="<?php echo e($aspirante->carrera?->nombre); ?>">
                                    <?php echo e($aspirante->carrera?->clave); ?>

                                </span>
                            </td>
                            <td><?php echo e($aspirante->periodo?->nombre); ?></td>
                            <td>
                                <span class="<?php echo e($aspirante->status_color); ?>"><?php echo e($aspirante->status_nombre); ?></span>
                            </td>
                            <td class="text-xs text-carbon-400"><?php echo e($aspirante->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="<?php echo e(route('escolar.aspirantes.show', $aspirante)); ?>"
                                       class="btn-icon btn-sm btn-secondary" title="Ver">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="<?php echo e(route('escolar.aspirantes.edit', $aspirante)); ?>"
                                       class="btn-icon btn-sm btn-secondary" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-10 text-carbon-400">
                                No se encontraron aspirantes con los filtros aplicados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($aspirantes->hasPages()): ?>
            <div class="px-4 py-3 border-t border-carbon-100">
                <?php echo e($aspirantes->links()); ?>

            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/aspirantes/index.blade.php ENDPATH**/ ?>