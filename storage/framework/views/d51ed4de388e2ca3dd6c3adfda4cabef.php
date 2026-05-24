<?php $__env->startSection('title', 'Nuevo Adeudo'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <a href="<?php echo e(route('escolar.adeudos.index')); ?>">Adeudos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registrar Adeudo</h3>
        </div>

        <form method="POST" action="<?php echo e(route('escolar.adeudos.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label class="form-label">Alumno <span class="text-danger">*</span></label>
                <select name="alumno_id" class="form-select" required>
                    <option value="">— Seleccionar alumno —</option>
                    <?php $__currentLoopData = $alumnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alumno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($alumno->id); ?>"
                            <?php echo e(old('alumno_id', $alumnoId) == $alumno->id ? 'selected' : ''); ?>>
                        <?php echo e($alumno->matricula); ?> — <?php echo e($alumno->nombre_completo); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['alumno_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipo <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(old('tipo') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <label class="form-label">Período (opcional)</label>
                    <select name="periodo_id" class="form-select">
                        <option value="">— Sin período —</option>
                        <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($periodo->id); ?>" <?php echo e(old('periodo_id') == $periodo->id ? 'selected' : ''); ?>>
                            <?php echo e($periodo->nombre); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">Concepto <span class="text-danger">*</span></label>
                <input type="text" name="concepto" value="<?php echo e(old('concepto')); ?>"
                       class="form-input" placeholder="Ej. Colegiatura enero 2026, Libro no devuelto..." required maxlength="150">
                <?php $__errorArgs = ['concepto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="form-label">Descripción adicional</label>
                <textarea name="descripcion" rows="2" class="form-input"
                          placeholder="Detalles o notas adicionales..."><?php echo e(old('descripcion')); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Monto (opcional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-carbon-400 text-sm">$</span>
                        <input type="number" name="monto" value="<?php echo e(old('monto')); ?>"
                               class="form-input pl-6" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="form-label">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" value="<?php echo e(old('fecha_vencimiento')); ?>"
                           class="form-input">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Registrar adeudo</button>
                <a href="<?php echo e(route('escolar.adeudos.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/adeudos/create.blade.php ENDPATH**/ ?>