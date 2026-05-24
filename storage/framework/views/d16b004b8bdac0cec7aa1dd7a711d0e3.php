<?php $__env->startSection('title', 'Nuevo Período'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <a href="<?php echo e(route('escolar.periodos.index')); ?>">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Crear Período Escolar</h3>
        </div>

        <form method="POST" action="<?php echo e(route('escolar.periodos.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nombre del período <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="<?php echo e(old('nombre')); ?>"
                           class="form-input" placeholder="Ej. 2026-A Enero-Junio" required>
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
                    <label class="form-label">Año <span class="text-danger">*</span></label>
                    <input type="number" name="anio" value="<?php echo e(old('anio', date('Y'))); ?>"
                           class="form-input" min="2020" max="2040" required>
                    <?php $__errorArgs = ['anio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipo de semestre <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="ene_jun" <?php echo e(old('tipo') === 'ene_jun' ? 'selected' : ''); ?>>Enero – Junio</option>
                        <option value="ago_dic" <?php echo e(old('tipo') === 'ago_dic' ? 'selected' : ''); ?>>Agosto – Diciembre</option>
                    </select>
                    <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Ciclo (letra) <span class="text-danger">*</span></label>
                    <select name="ciclo" class="form-select" required>
                        <option value="A" <?php echo e(old('ciclo') === 'A' ? 'selected' : ''); ?>>A (Enero–Junio)</option>
                        <option value="B" <?php echo e(old('ciclo') === 'B' ? 'selected' : ''); ?>>B (Agosto–Diciembre)</option>
                    </select>
                    <?php $__errorArgs = ['ciclo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_inicio" value="<?php echo e(old('fecha_inicio')); ?>"
                           class="form-input" required>
                    <?php $__errorArgs = ['fecha_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Fecha de fin <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_fin" value="<?php echo e(old('fecha_fin')); ?>"
                           class="form-input" required>
                    <?php $__errorArgs = ['fecha_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="max-w-xs">
                <label class="form-label">Número de semanas <span class="text-danger">*</span></label>
                <input type="number" name="num_semanas" value="<?php echo e(old('num_semanas', 16)); ?>"
                       class="form-input" min="1" max="26" required>
                <p class="form-help">El TECNM establece 16 semanas por semestre.</p>
                <?php $__errorArgs = ['num_semanas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Crear período</button>
                <a href="<?php echo e(route('escolar.periodos.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/periodos/create.blade.php ENDPATH**/ ?>