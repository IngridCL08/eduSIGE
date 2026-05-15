<?php $__env->startSection('title','Configuración del Sistema'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Configuración General de eduSIGE</h3>
    </div>
    <form method="POST" action="<?php echo e(route('admin.config.update')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div>
            <label class="form-label">Nombre de la Institución <span class="text-danger">*</span></label>
            <input name="institucion" value="<?php echo e(old('institucion', $config['institucion'])); ?>"
                   class="form-input <?php $__errorArgs = ['institucion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <?php $__errorArgs = ['institucion'];
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
                <label class="form-label">Monto de Ficha (MXN) <span class="text-danger">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-carbon-400 text-sm">$</span>
                    <input name="monto_ficha" type="number" step="0.01" min="1"
                           value="<?php echo e(old('monto_ficha', $config['monto_ficha'])); ?>"
                           class="form-input pl-7 <?php $__errorArgs = ['monto_ficha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                </div>
                <?php $__errorArgs = ['monto_ficha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="form-label">Días de vigencia de ficha <span class="text-danger">*</span></label>
                <input name="dias_vigencia_ficha" type="number" min="1" max="30"
                       value="<?php echo e(old('dias_vigencia_ficha', $config['dias_vigencia_ficha'])); ?>"
                       class="form-input <?php $__errorArgs = ['dias_vigencia_ficha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <p class="form-help">Días antes que expire la ficha</p>
                <?php $__errorArgs = ['dias_vigencia_ficha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p class="text-sm text-amber-800">
                <strong>Nota:</strong> Los cambios se aplicarán inmediatamente en el sistema. Asegúrate de que los datos sean correctos antes de guardar.
            </p>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar Configuración
            </button>
        </div>
    </form>
</div>


<div class="card mt-5">
    <div class="card-header"><h3 class="card-title">Información del Sistema</h3></div>
    <div class="space-y-2 text-sm">
        <?php $__currentLoopData = [
            ['PHP', PHP_VERSION],
            ['Laravel', app()->version()],
            ['Entorno', config('app.env')],
            ['Debug', config('app.debug') ? 'Activo' : 'Inactivo'],
            ['Zona horaria', config('app.timezone')],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-between py-2 border-b border-carbon-100 last:border-0">
            <span class="text-carbon-500"><?php echo e($label); ?></span>
            <code class="text-xs bg-carbon-100 px-2 py-0.5 rounded"><?php echo e($val); ?></code>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/admin/config/index.blade.php ENDPATH**/ ?>