<?php $__env->startSection('title', 'Mi Ficha de Pago'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-6">Mi Ficha de Pago</h2>

<?php if(! $ficha): ?>
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
    <p class="text-lg font-medium mb-2">No tienes una ficha de pago generada</p>
    <p class="text-sm">Contacta al área de Control Escolar para que te la asignen.</p>
</div>
<?php else: ?>


<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-xs text-slate-400">Folio de ficha</p>
            <p class="text-lg font-mono font-bold text-slate-800"><?php echo e($ficha->folio_ficha); ?></p>
        </div>
        <span class="badge <?php echo e($ficha->status_color); ?> text-sm px-3 py-1"><?php echo e($ficha->status_nombre); ?></span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-slate-400 text-xs">Monto</p>
            <p class="font-bold text-slate-800 text-lg"><?php echo e($ficha->monto_formateado); ?></p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Concepto</p>
            <p class="font-medium text-slate-700"><?php echo e($ficha->concepto); ?></p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Fecha emisión</p>
            <p class="font-medium text-slate-700"><?php echo e($ficha->fecha_emision->format('d/m/Y')); ?></p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Vencimiento</p>
            <p class="font-medium <?php echo e($ficha->fecha_vencimiento->isPast() ? 'text-red-600' : 'text-slate-700'); ?>">
                <?php echo e($ficha->fecha_vencimiento->format('d/m/Y')); ?>

            </p>
        </div>
    </div>

    <?php if($ficha->status === 'pagado'): ?>
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
        ✓ Pago confirmado el <?php echo e($ficha->fecha_pago?->format('d/m/Y H:i')); ?>

        — Método: <?php echo e($ficha->metodo_pago); ?>

        <?php if($ficha->referencia_pago): ?> — Ref: <?php echo e($ficha->referencia_pago); ?> <?php endif; ?>
    </div>
    <?php endif; ?>
</div>


<?php if($ficha->status === 'pendiente' && ! $ficha->fecha_vencimiento->isPast()): ?>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Pagar en línea</h3>
        <p class="text-sm text-slate-500 mb-4">Paga de forma segura con tarjeta de crédito o débito.</p>
        <form method="POST" action="<?php echo e(route('portal.aspirante.ficha.pagar')); ?>" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="gateway" value="conekta">
            <button class="btn-primary w-full justify-center">
                Pagar con tarjeta (Conekta)
            </button>
        </form>
        <form method="POST" action="<?php echo e(route('portal.aspirante.ficha.pagar')); ?>" class="mt-2">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="gateway" value="paypal">
            <button class="btn-secondary w-full justify-center mt-2">
                Pagar con PayPal
            </button>
        </form>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Transferencia bancaria</h3>

        <?php
            $clabe  = config('app.edusige.clabe_bancaria', '000000000000000000');
            $banco  = config('app.edusige.banco', 'Institución');
            $ref    = $ficha->referencia_bancaria ?? $ficha->folio_ficha;
        ?>

        <div class="text-sm space-y-2 mb-4">
            <div class="flex justify-between">
                <span class="text-slate-500">Banco:</span>
                <span class="font-medium text-slate-800"><?php echo e($banco); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">CLABE:</span>
                <span class="font-mono font-medium text-slate-800"><?php echo e($clabe); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Referencia:</span>
                <span class="font-mono font-bold text-indigo-700"><?php echo e($ref); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Monto exacto:</span>
                <span class="font-bold text-slate-800"><?php echo e($ficha->monto_formateado); ?></span>
            </div>
        </div>

        <p class="text-xs text-slate-400 mb-3">Sube tu comprobante después de realizar la transferencia.</p>

        <form method="POST" action="<?php echo e(route('portal.aspirante.ficha.comprobante')); ?>"
              enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                   class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3
                          file:rounded file:border-0 file:text-xs file:bg-indigo-50
                          file:text-indigo-700 hover:file:bg-indigo-100 mb-2">
            <?php $__errorArgs = ['comprobante'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-xs mb-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <button class="btn-primary btn-sm w-full justify-center">
                Subir comprobante
            </button>
        </form>
    </div>
</div>
<?php endif; ?>


<?php if($aspirante->fichaPago?->comprobantes?->isNotEmpty()): ?>
<div class="bg-white rounded-xl border border-slate-200 p-5">
    <h3 class="font-semibold text-slate-800 mb-3">Comprobantes enviados</h3>
    <div class="space-y-2">
        <?php $__currentLoopData = $aspirante->fichaPago->comprobantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg text-sm">
            <div>
                <p class="font-medium text-slate-700"><?php echo e($comp->nombre_original); ?></p>
                <p class="text-xs text-slate-400"><?php echo e($comp->created_at->format('d/m/Y H:i')); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge <?php echo e($comp->status_color); ?>"><?php echo e($comp->status_nombre); ?></span>
                <a href="<?php echo e($comp->url); ?>" target="_blank"
                   class="text-indigo-600 text-xs hover:underline">Ver</a>
            </div>
        </div>
        <?php if($comp->observaciones && $comp->status === 'rechazado'): ?>
        <p class="text-xs text-red-600 px-3">Motivo de rechazo: <?php echo e($comp->observaciones); ?></p>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-aspirante', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/aspirante/ficha.blade.php ENDPATH**/ ?>