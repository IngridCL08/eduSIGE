<?php $__env->startSection('title', 'Nueva Materia'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <a href="<?php echo e(route('escolar.materias.index')); ?>">Materias</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nueva</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nueva Materia</h3>
        </div>

        <form method="POST" action="<?php echo e(route('escolar.materias.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Clave <span class="text-danger">*</span></label>
                    <input type="text" name="clave" value="<?php echo e(old('clave')); ?>"
                           class="form-input font-mono uppercase" placeholder="Ej. MAT-101" required maxlength="20">
                    <p class="form-help">Identificador único de la materia.</p>
                    <?php $__errorArgs = ['clave'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Semestre sugerido</label>
                    <select name="semestre_sugerido" class="form-select">
                        <option value="">— Sin asignar —</option>
                        <?php for($i = 1; $i <= 9; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(old('semestre_sugerido') == $i ? 'selected' : ''); ?>>
                            Semestre <?php echo e($i); ?>

                        </option>
                        <?php endfor; ?>
                    </select>
                    <?php $__errorArgs = ['semestre_sugerido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div>
                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre" value="<?php echo e(old('nombre')); ?>"
                       class="form-input" placeholder="Ej. Matemáticas Discretas" required maxlength="150">
                <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Créditos <span class="text-danger">*</span></label>
                    <input type="number" name="creditos" value="<?php echo e(old('creditos', 5)); ?>"
                           class="form-input" min="1" max="20" required>
                    <?php $__errorArgs = ['creditos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Horas teoría <span class="text-danger">*</span></label>
                    <input type="number" name="horas_teoria" value="<?php echo e(old('horas_teoria', 3)); ?>"
                           class="form-input" min="0" max="20" required>
                    <?php $__errorArgs = ['horas_teoria'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Horas práctica <span class="text-danger">*</span></label>
                    <input type="number" name="horas_practica" value="<?php echo e(old('horas_practica', 2)); ?>"
                           class="form-input" min="0" max="20" required>
                    <?php $__errorArgs = ['horas_practica'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="bg-carbon-50 rounded-lg px-4 py-3 text-sm text-carbon-600">
                <span class="font-medium text-carbon-950">Fórmula TECNM:</span>
                Créditos = (Horas teoría × 2) + (Horas práctica × 1)
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Guardar materia</button>
                <a href="<?php echo e(route('escolar.materias.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/materias/create.blade.php ENDPATH**/ ?>