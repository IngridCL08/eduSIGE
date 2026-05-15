<?php $__env->startSection('title','Aspirante — ' . $aspirante->nombre_completo); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('financiero.aspirantes.index')); ?>">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium"><?php echo e($aspirante->folio); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <?php if($aspirante->puedeGenerarFicha()): ?>
    <form method="POST" action="<?php echo e(route('financiero.aspirantes.generar-ficha', $aspirante)); ?>">
        <?php echo csrf_field(); ?>
        <button class="btn-primary btn-sm">Generar Ficha de Pago</button>
    </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Aspirante</h3>
                <span class="badge <?php echo e($aspirante->status_color); ?>"><?php echo e($aspirante->status_nombre); ?></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <?php $__currentLoopData = [
                    ['Folio',       $aspirante->folio],
                    ['Nombre',      $aspirante->nombre_completo],
                    ['CURP',        $aspirante->curp ?? '—'],
                    ['Email',       $aspirante->email],
                    ['Teléfono',    $aspirante->telefono ?? '—'],
                    ['Sexo',        $aspirante->sexo === 'M' ? 'Masculino' : ($aspirante->sexo === 'F' ? 'Femenino' : '—')],
                    ['Carrera',     $aspirante->carrera?->nombre ?? '—'],
                    ['Período',     $aspirante->periodo?->nombre ?? '—'],
                    ['Registrado',  $aspirante->created_at->format('d/m/Y')],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <p class="text-carbon-500 text-xs mb-0.5"><?php echo e($label); ?></p>
                    <p class="font-medium text-carbon-900 break-all"><?php echo e($val); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fichas de Pago</h3>
                <span class="text-sm text-carbon-400"><?php echo e($aspirante->fichas->count()); ?> ficha(s)</span>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $aspirante->fichas()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ficha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border border-carbon-100 rounded-xl p-4 mb-3 last:mb-0">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-mono text-sm font-bold text-navy-800"><?php echo e($ficha->folio_ficha); ?></p>
                        <p class="text-xs text-carbon-400 mt-0.5"><?php echo e($ficha->concepto); ?></p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-lg font-black text-carbon-900"><?php echo e($ficha->monto_formateado); ?></p>
                        <span class="badge text-xs <?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-3 text-xs text-carbon-500">
                    <div>
                        <span class="block">Emisión</span>
                        <span class="font-medium text-carbon-700"><?php echo e($ficha->fecha_emision->format('d/m/Y')); ?></span>
                    </div>
                    <div>
                        <span class="block">Vencimiento</span>
                        <span class="font-medium text-carbon-700"><?php echo e($ficha->fecha_vencimiento->format('d/m/Y')); ?></span>
                    </div>
                    <div>
                        <span class="block">Pago</span>
                        <span class="font-medium text-carbon-700"><?php echo e($ficha->fecha_pago?->format('d/m/Y') ?? '—'); ?></span>
                    </div>
                </div>
                <?php if($ficha->metodo_pago): ?>
                <p class="text-xs text-carbon-400 mt-2">
                    Método: <span class="font-medium"><?php echo e(ucfirst($ficha->metodo_pago)); ?></span>
                    <?php if($ficha->referencia_pago): ?>
                        · Ref: <span class="font-mono"><?php echo e($ficha->referencia_pago); ?></span>
                    <?php endif; ?>
                </p>
                <?php endif; ?>
                <div class="flex justify-end gap-2 mt-3">
                    <a href="<?php echo e(route('financiero.fichas.show', $ficha)); ?>" class="btn-outline btn-sm">Detalle</a>
                    <a href="<?php echo e(route('financiero.fichas.pdf', $ficha)); ?>" target="_blank" class="btn-secondary btn-sm">PDF</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-8 text-center text-carbon-400">
                <p class="text-sm">Este aspirante aún no tiene fichas de pago.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>

    
    <div class="space-y-5">

        
        <div class="card">
            <div class="card-header"><h3 class="card-title">Resumen Financiero</h3></div>
            <div class="space-y-3">
                <?php
                    $fichas      = $aspirante->fichas;
                    $totalPagado = $fichas->where('status','pagado')->sum('monto');
                    $pendientes  = $fichas->where('status','pendiente')->count();
                ?>
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Total fichas</span>
                    <span class="font-semibold"><?php echo e($fichas->count()); ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Fichas pagadas</span>
                    <span class="font-semibold text-green-700"><?php echo e($fichas->where('status','pagado')->count()); ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Fichas pendientes</span>
                    <span class="font-semibold text-amber-600"><?php echo e($pendientes); ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-carbon-500">Total pagado</span>
                    <span class="font-bold text-navy-800">$<?php echo e(number_format($totalPagado, 2)); ?></span>
                </div>
            </div>
        </div>

        
        <?php if($aspirante->puedeGenerarFicha()): ?>
        <div class="card border-2 border-dashed border-navy-300 bg-navy-50/30">
            <p class="text-sm text-carbon-600 mb-3">Este aspirante no tiene una ficha activa.</p>
            <form method="POST" action="<?php echo e(route('financiero.aspirantes.generar-ficha', $aspirante)); ?>">
                <?php echo csrf_field(); ?>
                <button class="btn-primary w-full justify-center">Generar Ficha de Pago</button>
            </form>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/aspirantes/show.blade.php ENDPATH**/ ?>