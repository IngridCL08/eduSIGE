<?php $__env->startSection('title','Aspirantes'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Aspirantes</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar</label>
            <input name="buscar" value="<?php echo e(request('buscar')); ?>" class="form-input"
                   placeholder="Folio, nombre, CURP, email…">
        </div>
        <div>
            <label class="form-label">Período</label>
            <select name="periodo_id" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('periodo_id') == $p->id ? 'selected' : ''); ?>>
                    <?php echo e($p->nombre); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Estado de Ficha</label>
            <select name="ficha_status" class="form-select">
                <option value="">Todos</option>
                <option value="sin_ficha"  <?php echo e(request('ficha_status') === 'sin_ficha'  ? 'selected' : ''); ?>>Sin ficha</option>
                <option value="pendiente"  <?php echo e(request('ficha_status') === 'pendiente'  ? 'selected' : ''); ?>>Pendiente</option>
                <option value="pagado"     <?php echo e(request('ficha_status') === 'pagado'     ? 'selected' : ''); ?>>Pagada</option>
                <option value="vencido"    <?php echo e(request('ficha_status') === 'vencido'    ? 'selected' : ''); ?>>Vencida</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="<?php echo e(route('financiero.aspirantes.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>


<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Ficha</th>
                    <th>Estado Ficha</th>
                    <th>Monto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $aspirantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $ficha = $asp->fichaPago; ?>
                <tr>
                    <td class="font-mono text-xs"><?php echo e($asp->folio); ?></td>
                    <td>
                        <div class="font-medium text-carbon-900"><?php echo e($asp->nombre_completo); ?></div>
                        <div class="text-xs text-carbon-400"><?php echo e($asp->email); ?></div>
                    </td>
                    <td class="text-sm"><?php echo e($asp->carrera?->clave ?? '—'); ?></td>
                    <td class="text-sm whitespace-nowrap"><?php echo e($asp->periodo?->nombre ?? '—'); ?></td>
                    <td class="font-mono text-xs"><?php echo e($ficha?->folio_ficha ?? '—'); ?></td>
                    <td>
                        <?php if($ficha): ?>
                            <span class="badge <?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span>
                        <?php else: ?>
                            <span class="badge badge-neutral">Sin ficha</span>
                        <?php endif; ?>
                    </td>
                    <td class="font-medium"><?php echo e($ficha ? $ficha->monto_formateado : '—'); ?></td>
                    <td class="text-right">
                        <a href="<?php echo e(route('financiero.aspirantes.show', $asp)); ?>"
                           class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-10 text-carbon-400">
                        No se encontraron aspirantes con los filtros aplicados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($aspirantes->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($aspirantes->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/aspirantes/index.blade.php ENDPATH**/ ?>