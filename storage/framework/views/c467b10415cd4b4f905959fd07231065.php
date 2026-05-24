<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-6">Mi Perfil</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Datos académicos</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Matrícula</dt>
                <dd class="font-mono font-bold text-slate-800"><?php echo e($alumno->matricula); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Nombre completo</dt>
                <dd class="font-medium text-slate-800 text-right max-w-[200px]"><?php echo e($alumno->nombre_completo); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Carrera</dt>
                <dd class="font-medium text-slate-800 text-right max-w-[200px]"><?php echo e($alumno->carrera?->nombre ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Período de ingreso</dt>
                <dd class="text-slate-800"><?php echo e($alumno->periodoIngreso?->nombre ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Estatus</dt>
                <dd><span class="badge <?php echo e($alumno->status_color); ?>"><?php echo e($alumno->status_nombre); ?></span></dd>
            </div>
        </dl>
    </div>

    
    <?php $asp = $alumno->aspirante; ?>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Datos de contacto</h3>

        <form method="POST" action="<?php echo e(route('portal.alumno.perfil.update')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" value="<?php echo e(old('telefono', $asp?->telefono)); ?>"
                       class="form-input" placeholder="10 dígitos">
            </div>
            <div>
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" value="<?php echo e(old('direccion', $asp?->direccion)); ?>"
                       class="form-input" placeholder="Calle y número">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Colonia</label>
                    <input type="text" name="colonia" value="<?php echo e(old('colonia', $asp?->colonia)); ?>"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">C.P.</label>
                    <input type="text" name="cp" value="<?php echo e(old('cp', $asp?->cp)); ?>"
                           class="form-input" maxlength="5">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" value="<?php echo e(old('municipio', $asp?->municipio)); ?>"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <input type="text" name="estado" value="<?php echo e(old('estado', $asp?->estado)); ?>"
                           class="form-input">
                </div>
            </div>
            <button type="submit" class="btn-primary w-full justify-center"
                    style="background-color:#059669">
                Guardar cambios
            </button>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/perfil.blade.php ENDPATH**/ ?>