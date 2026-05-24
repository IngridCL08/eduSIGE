<?php $__env->startSection('title', 'Editar Período'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <a href="<?php echo e(route('escolar.periodos.index')); ?>">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium"><?php echo e($periodo->nombre); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl space-y-6">

    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Período</h3>
            <span class="<?php echo e($periodo->estado_color); ?>"><?php echo e(ucfirst($periodo->estado ?? 'planeacion')); ?></span>
        </div>

        <form method="POST" action="<?php echo e(route('escolar.periodos.update', $periodo)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="form-label">Nombre del período</label>
                    <input type="text" name="nombre" value="<?php echo e(old('nombre', $periodo->nombre)); ?>"
                           class="form-input" required>
                    <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" value="<?php echo e(old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d'))); ?>"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Fecha de fin</label>
                    <input type="date" name="fecha_fin" value="<?php echo e(old('fecha_fin', $periodo->fecha_fin->format('Y-m-d'))); ?>"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Total de semanas</label>
                    <input type="number" name="num_semanas" value="<?php echo e(old('num_semanas', $periodo->num_semanas)); ?>"
                           class="form-input" min="1" max="26" required>
                </div>
                <div>
                    <label class="form-label">Semana actual</label>
                    <input type="number" name="semana_actual" value="<?php echo e(old('semana_actual', $periodo->semana_actual)); ?>"
                           class="form-input" min="1" max="<?php echo e($periodo->num_semanas); ?>">
                    <p class="form-help">Puede ajustar manualmente la semana.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="<?php echo e(route('escolar.periodos.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    
    <?php if($periodo->estado !== 'cerrado'): ?>
    <div class="card border border-red-200 bg-red-50">
        <h3 class="text-base font-semibold text-red-800 mb-3">Cerrar período definitivamente</h3>
        <p class="text-sm text-red-600 mb-4">
            Una vez cerrado, el período no podrá reactivarse. Asegúrese de que todas las calificaciones estén capturadas.
        </p>
        <form method="POST" action="<?php echo e(route('escolar.periodos.cerrar', $periodo)); ?>"
              onsubmit="return confirm('¿Confirmar cierre definitivo del período?')">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <div class="mb-3">
                <label class="form-label text-red-700">Motivo del cierre</label>
                <input type="text" name="motivo_cierre" class="form-input"
                       placeholder="Ej. Semestre concluido, calificaciones capturadas" required>
                <?php $__errorArgs = ['motivo_cierre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn-danger btn-sm">Cerrar período</button>
        </form>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/periodos/edit.blade.php ENDPATH**/ ?>