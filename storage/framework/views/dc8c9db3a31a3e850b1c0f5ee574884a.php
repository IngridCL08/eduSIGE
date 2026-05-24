<?php $__env->startSection('title', 'Mis Pagos'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-5">Mis Pagos</h2>


<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="bg-emerald-100 rounded-lg p-2.5">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-emerald-700">$<?php echo e(number_format($totalPagado, 2)); ?></p>
            <p class="text-xs text-slate-500">Total pagado (<?php echo e($countPagados); ?> pago<?php echo e($countPagados !== 1 ? 's' : ''); ?>)</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="bg-amber-100 rounded-lg p-2.5">
            <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-amber-700">$<?php echo e(number_format($totalPendiente, 2)); ?></p>
            <p class="text-xs text-slate-500">Pendiente (<?php echo e($countPendientes); ?> adeudo<?php echo e($countPendientes !== 1 ? 's' : ''); ?>)</p>
        </div>
    </div>
</div>


<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Concepto</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Monto</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Método</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800"><?php echo e($pago->concepto); ?></p>
                        <?php if($pago->periodo): ?>
                        <p class="text-xs text-slate-400"><?php echo e($pago->periodo->nombre); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if($pago->tipo): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                            <?php echo e($tipos[$pago->tipo] ?? $pago->tipo); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-slate-800">
                        <?php echo e($pago->monto ? '$' . number_format($pago->monto, 2) : '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($pago->status === 'pagado'): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            Pagado
                        </span>
                        <?php elseif($pago->status === 'vencido'): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                            Vencido
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                            Pendiente
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        <?php if($pago->status === 'pagado'): ?>
                            <p><?php echo e($pago->fecha_pago?->format('d/m/Y') ?? '—'); ?></p>
                            <?php if($pago->validadoPor): ?>
                            <p class="text-xs text-slate-400">Validado por <?php echo e($pago->validadoPor->name); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-slate-400">Vence: <?php echo e($pago->fecha_vencimiento?->format('d/m/Y') ?? '—'); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <?php if($pago->status === 'pagado' && $pago->metodo_pago): ?>
                        <span class="text-slate-600"><?php echo e($metodos[$pago->metodo_pago] ?? $pago->metodo_pago); ?></span>
                        <?php elseif($pago->referencia_pago): ?>
                        <span class="text-xs text-slate-400 font-mono"><?php echo e($pago->referencia_pago); ?></span>
                        <?php else: ?>
                        <span class="text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                        No hay pagos registrados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/pagos.blade.php ENDPATH**/ ?>