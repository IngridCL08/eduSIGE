<?php $__env->startSection('title','Fichas de Pago — Reporte'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Reporte de Fichas</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('financiero.reportes.exportar', array_merge(request()->all(), ['tipo'=>'fichas']))); ?>"
       class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar Excel
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar aspirante</label>
            <input name="buscar" value="<?php echo e(request('buscar')); ?>" class="form-input" placeholder="Folio, nombre…">
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
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = ['pendiente'=>'Pendiente','pagado'=>'Pagada','vencido'=>'Vencida','cancelado'=>'Cancelada']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>" <?php echo e(request('status') === $k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Método pago</label>
            <select name="metodo_pago" class="form-select">
                <option value="">Todos</option>
                <?php $__currentLoopData = ['conekta'=>'Conekta','paypal'=>'PayPal','transferencia'=>'Transferencia','efectivo'=>'Efectivo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>" <?php echo e(request('metodo_pago') === $k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="<?php echo e(route('financiero.reportes.fichas')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
    <?php $__currentLoopData = [
        ['Total',      $resumen['total'],     'text-carbon-900',  'bg-carbon-100'],
        ['Pagadas',    $resumen['pagadas'],    'text-green-700',   'bg-green-100'],
        ['Pendientes', $resumen['pendientes'], 'text-amber-700',   'bg-amber-100'],
        ['Vencidas',   $resumen['vencidas'],   'text-red-700',     'bg-red-100'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $textCls, $bgCls]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-xl <?php echo e($bgCls); ?> px-4 py-3">
        <p class="text-2xl font-black <?php echo e($textCls); ?>"><?php echo e(number_format($count)); ?></p>
        <p class="text-xs text-carbon-500 mt-0.5"><?php echo e($label); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio Ficha</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Método</th>
                    <th>Emisión</th>
                    <th>Vencimiento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $fichas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ficha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="font-mono text-xs font-bold"><?php echo e($ficha->folio_ficha); ?></td>
                    <td>
                        <div class="font-medium text-sm"><?php echo e($ficha->aspirante->nombre_completo); ?></div>
                        <div class="text-xs text-carbon-400 font-mono"><?php echo e($ficha->aspirante->folio); ?></div>
                    </td>
                    <td class="text-sm"><?php echo e($ficha->aspirante->carrera?->clave ?? '—'); ?></td>
                    <td class="text-sm whitespace-nowrap"><?php echo e($ficha->aspirante->periodo?->nombre ?? '—'); ?></td>
                    <td class="font-semibold"><?php echo e($ficha->monto_formateado); ?></td>
                    <td><span class="badge <?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span></td>
                    <td class="text-sm"><?php echo e($ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—'); ?></td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap"><?php echo e($ficha->fecha_emision->format('d/m/Y')); ?></td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap"><?php echo e($ficha->fecha_vencimiento->format('d/m/Y')); ?></td>
                    <td class="text-right">
                        <a href="<?php echo e(route('financiero.fichas.show', $ficha)); ?>" class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center py-10 text-carbon-400">
                        No hay fichas con los filtros aplicados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($fichas->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($fichas->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/reportes/fichas.blade.php ENDPATH**/ ?>