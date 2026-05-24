<!DOCTYPE html>
<html lang="es" class="h-full bg-emerald-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña — <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full flex items-center justify-center px-4">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-emerald-700"><?php echo e(config('app.name')); ?></h1>
        <p class="text-slate-500 mt-1">Portal de Alumnos</p>
    </div>

    <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 mb-4 text-sm text-amber-800">
        <p class="font-semibold">¡Bienvenido, <?php echo e(auth('alumno')->user()->aspirante->nombre); ?>!</p>
        <p class="mt-1">Por seguridad, debes establecer una nueva contraseña antes de continuar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Establecer nueva contraseña</h2>

        <?php if($errors->any()): ?>
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('portal.alumno.password.update')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label class="form-label">Nueva contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       autocomplete="new-password"
                       placeholder="Mínimo 8 caracteres">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="form-label">Confirmar contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                       class="form-input"
                       autocomplete="new-password"
                       placeholder="Repite la contraseña">
            </div>

            <button type="submit" class="btn-primary w-full justify-center" style="background-color:#059669">
                Guardar contraseña y continuar
            </button>
        </form>
    </div>

    <div class="text-center mt-4">
        <form method="POST" action="<?php echo e(route('portal.alumno.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/cambiar-password.blade.php ENDPATH**/ ?>