<?php $__env->startSection('title', 'Mis Adeudos'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-6">Mis Adeudos</h2>


<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-amber-600">$<?php echo e(number_format($totalPendiente, 2)); ?></p>
        <p class="text-xs text-slate-400 mt-1">Total pendiente</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-red-600">$<?php echo e(number_format($totalVencido, 2)); ?></p>
        <p class="text-xs text-slate-400 mt-1">Total vencido</p>
    </div>
</div>

<?php $__empty_1 = true; $__currentLoopData = $adeudos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adeudo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="bg-white rounded-xl border <?php echo e($adeudo->status === 'vencido' ? 'border-red-200' : ($adeudo->status === 'pendiente' ? 'border-amber-200' : 'border-slate-200')); ?> p-5 mb-3">
    <div class="flex items-start justify-between">
        <div>
            <p class="font-semibold text-slate-800"><?php echo e($adeudo->concepto); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">
                <?php echo e($adeudo->periodo?->nombre ?? 'Sin período'); ?>

                <?php if($adeudo->fecha_vencimiento): ?>
                    · Vence: <?php echo e($adeudo->fecha_vencimiento->format('d/m/Y')); ?>

                    <?php if($adeudo->fecha_vencimiento->isPast() && $adeudo->status === 'pendiente'): ?>
                        <span class="text-red-500 font-medium">(vencido)</span>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="text-right">
            <p class="text-lg font-bold text-slate-800"><?php echo e($adeudo->monto_formateado); ?></p>
            <span class="badge <?php echo e($adeudo->status_color); ?>"><?php echo e($adeudo->status_nombre); ?></span>
        </div>
    </div>

    <?php if($adeudo->status === 'pagado' && $adeudo->fecha_pago): ?>
    <p class="text-xs text-green-600 mt-2">
        Pagado el <?php echo e($adeudo->fecha_pago->format('d/m/Y')); ?>

        <?php if($adeudo->referencia_pago): ?> — Ref: <?php echo e($adeudo->referencia_pago); ?> <?php endif; ?>
    </p>
    <?php endif; ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
    <p class="text-slate-400 font-medium">No tienes adeudos registrados.</p>
</div>
<?php endif; ?>

<p class="text-xs text-slate-400 mt-4">
    Para aclarar adeudos o registrar pagos, acude a la ventanilla de Recursos Financieros.
</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/adeudos.blade.php ENDPATH**/ ?>