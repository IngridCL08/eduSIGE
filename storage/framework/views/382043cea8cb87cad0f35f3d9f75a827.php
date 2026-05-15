<?php $__env->startSection('title', 'Fichas de Pago'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Fichas de Pago</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('financiero.reportes.exportar', ['tipo' => 'fichas'] + request()->only(['status','metodo_pago','carrera_id']))); ?>"
       class="btn-outline btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Exportar Excel
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <form method="GET" action="<?php echo e(route('financiero.fichas.index')); ?>" class="card mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input name="buscar" value="<?php echo e(request('buscar')); ?>" type="text"
                       class="form-input" placeholder="Folio, nombre, CURP…">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = ['pendiente' => 'Pendiente', 'pagado' => 'Pagado', 'vencido' => 'Vencido', 'cancelado' => 'Cancelado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(request('status') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Método de pago</label>
                <select name="metodo_pago" class="form-select">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = ['conekta' => 'Conekta', 'paypal' => 'PayPal', 'transferencia' => 'Transferencia', 'efectivo' => 'Efectivo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(request('metodo_pago') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Carrera</label>
                <select name="carrera_id" class="form-select">
                    <option value="">Todas</option>
                    <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carrera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($carrera->id); ?>" <?php echo e(request('carrera_id') == $carrera->id ? 'selected' : ''); ?>>
                            <?php echo e($carrera->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <a href="<?php echo e(route('financiero.fichas.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
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
                        <th>Folio Ficha</th>
                        <th>Aspirante</th>
                        <th>Carrera</th>
                        <th>Monto</th>
                        <th>Emisión</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Método</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $fichas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ficha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('financiero.fichas.show', $ficha)); ?>"
                                   class="font-mono text-navy-800 font-medium hover:underline">
                                    <?php echo e($ficha->folio_ficha); ?>

                                </a>
                            </td>
                            <td>
                                <div class="font-medium text-carbon-900"><?php echo e($ficha->aspirante?->nombre_completo); ?></div>
                                <div class="text-xs text-carbon-400"><?php echo e($ficha->aspirante?->folio); ?></div>
                            </td>
                            <td class="max-w-[160px] truncate"><?php echo e($ficha->aspirante?->carrera?->nombre); ?></td>
                            <td class="font-semibold"><?php echo e($ficha->monto_formateado); ?></td>
                            <td><?php echo e($ficha->fecha_emision->format('d/m/Y')); ?></td>
                            <td>
                                <span class="<?php echo e($ficha->fecha_vencimiento->isPast() && $ficha->status === 'pendiente' ? 'text-danger font-medium' : ''); ?>">
                                    <?php echo e($ficha->fecha_vencimiento->format('d/m/Y')); ?>

                                </span>
                            </td>
                            <td><span class="<?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span></td>
                            <td><?php echo e($ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—'); ?></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="<?php echo e(route('financiero.fichas.show', $ficha)); ?>"
                                       class="btn-icon btn-sm btn-secondary" title="Ver detalle">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="<?php echo e(route('financiero.fichas.pdf', $ficha)); ?>"
                                       class="btn-icon btn-sm btn-secondary" title="Descargar PDF" target="_blank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-10 text-carbon-400">
                                No se encontraron fichas con los filtros aplicados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($fichas->hasPages()): ?>
            <div class="px-4 py-3 border-t border-carbon-100">
                <?php echo e($fichas->links()); ?>

            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/fichas/index.blade.php ENDPATH**/ ?>