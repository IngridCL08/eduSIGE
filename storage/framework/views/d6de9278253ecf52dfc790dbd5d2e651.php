<?php $__env->startSection('title', 'Mis Documentos'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-2">Mis Documentos</h2>
<p class="text-sm text-slate-500 mb-6">
    Sube los documentos requeridos en formato JPG, PNG o PDF (máx. 8 MB cada uno).
</p>

<?php if(! $aspirante->tieneFichaPagada()): ?>
<div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg px-4 py-3 text-sm mb-6">
    ⚠ Debes tener la ficha de pago cubierta antes de subir documentos.
    <a href="<?php echo e(route('portal.aspirante.ficha')); ?>" class="underline font-medium">Ir a mi ficha →</a>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clave => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $doc = $documentos->firstWhere('tipo', $clave);
    ?>
    <div class="bg-white rounded-xl border <?php echo e($doc ? ($doc->verificado ? 'border-green-200' : 'border-amber-200') : 'border-slate-200'); ?> p-5">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="font-semibold text-slate-800 text-sm"><?php echo e($nombre); ?></p>
                <?php if($doc): ?>
                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[200px]"><?php echo e($doc->nombre_archivo); ?></p>
                <?php endif; ?>
            </div>
            <?php if($doc): ?>
                <?php if($doc->verificado): ?>
                    <span class="badge badge-success">Verificado</span>
                <?php else: ?>
                    <span class="badge badge-warning">En revisión</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="badge badge-neutral">Pendiente</span>
            <?php endif; ?>
        </div>

        <?php if($aspirante->tieneFichaPagada()): ?>
        <form method="POST" action="<?php echo e(route('portal.aspirante.documentos.store')); ?>"
              enctype="multipart/form-data" class="mt-2">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipo" value="<?php echo e($clave); ?>">
            <div class="flex gap-2">
                <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf"
                       class="flex-1 text-xs text-slate-600 file:mr-2 file:py-1 file:px-2
                              file:rounded file:border-0 file:text-xs file:bg-indigo-50
                              file:text-indigo-700 hover:file:bg-indigo-100">
                <button class="btn-primary btn-sm flex-shrink-0">
                    <?php echo e($doc ? 'Reemplazar' : 'Subir'); ?>

                </button>
            </div>
        </form>
        <?php endif; ?>

        <?php if($doc && $doc->verificado_at): ?>
        <p class="text-xs text-green-600 mt-2">
            Verificado el <?php echo e($doc->verificado_at->format('d/m/Y')); ?>

        </p>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-aspirante', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/aspirante/documentos.blade.php ENDPATH**/ ?>