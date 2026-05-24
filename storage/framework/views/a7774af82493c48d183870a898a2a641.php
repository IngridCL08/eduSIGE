<?php $__env->startSection('title', 'Calendario de Reinscripción'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Calendario de Reinscripción</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <select name="periodo_id" class="form-select w-56">
            <option value="">— Todos los períodos —</option>
            <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($p->id); ?>" <?php echo e(request('periodo_id') == $p->id ? 'selected' : ''); ?>>
                <?php echo e($p->nombre); ?>

            </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit" class="btn-primary btn-sm">Filtrar</button>
        <a href="<?php echo e(route('escolar.calendario.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    
    <div class="xl:col-span-2 card p-0">
        <div class="card-header px-6 pt-5 pb-4">
            <h3 class="card-title">
                Ventanas de Reinscripción
                <?php if($periodo): ?>
                    <span class="text-sm font-normal text-carbon-500">— <?php echo e($periodo->nombre); ?></span>
                <?php endif; ?>
            </h3>
        </div>
        <div class="table-wrapper rounded-t-none border-t-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Carrera / Semestre</th>
                        <th>Período</th>
                        <th>Turno</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th class="text-center">Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $calendarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <p class="font-medium text-carbon-950"><?php echo e($cal->carrera->nombre ?? '—'); ?></p>
                            <p class="text-xs text-carbon-400">Semestre <?php echo e($cal->semestre); ?>°</p>
                        </td>
                        <td class="text-sm text-carbon-600"><?php echo e($cal->periodo->nombre ?? '—'); ?></td>
                        <td>
                            <span class="badge-info badge text-xs capitalize"><?php echo e($cal->turno); ?></span>
                        </td>
                        <td class="text-xs text-carbon-700">
                            <?php echo e(\Carbon\Carbon::parse($cal->fecha_hora_inicio)->format('d/m/Y H:i')); ?>

                        </td>
                        <td class="text-xs text-carbon-700">
                            <?php echo e(\Carbon\Carbon::parse($cal->fecha_hora_fin)->format('d/m/Y H:i')); ?>

                        </td>
                        <td class="text-center">
                            <?php $estado = $cal->estado; ?>
                            <span class="<?php echo e($estado['color']); ?> text-xs"><?php echo e($estado['label']); ?></span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="<?php echo e(route('escolar.calendario.toggle', $cal)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                            class="<?php echo e($cal->activo ? 'btn-danger' : 'btn-success'); ?> btn-sm">
                                        <?php echo e($cal->activo ? 'Suspender' : 'Activar'); ?>

                                    </button>
                                </form>
                                <form method="POST" action="<?php echo e(route('escolar.calendario.destroy', $cal)); ?>"
                                      onsubmit="return confirm('¿Eliminar esta ventana?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-secondary btn-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-10 text-carbon-400">No hay ventanas configuradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card">
        <h3 class="card-title mb-4">Nueva ventana</h3>
        <form method="POST" action="<?php echo e(route('escolar.calendario.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="form-label">Período <span class="text-danger">*</span></label>
                <select name="periodo_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e((request('periodo_id') ?? optional($periodo)->id) == $p->id ? 'selected' : ''); ?>>
                        <?php echo e($p->nombre); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['periodo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="form-label">Carrera <span class="text-danger">*</span></label>
                <select name="carrera_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carrera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($carrera->id); ?>"><?php echo e($carrera->clave); ?> — <?php echo e($carrera->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['carrera_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Semestre <span class="text-danger">*</span></label>
                    <select name="semestre" class="form-select" required>
                        <?php for($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?>°</option>
                        <?php endfor; ?>
                    </select>
                    <?php $__errorArgs = ['semestre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Turno <span class="text-danger">*</span></label>
                    <select name="turno" class="form-select" required>
                        <option value="todos">Todos</option>
                        <option value="matutino">Matutino</option>
                        <option value="vespertino">Vespertino</option>
                        <option value="mixto">Mixto</option>
                    </select>
                    <?php $__errorArgs = ['turno'];
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
                <label class="form-label">Apertura <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_hora_inicio" class="form-input"
                       value="<?php echo e(old('fecha_hora_inicio')); ?>" required>
                <?php $__errorArgs = ['fecha_hora_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="form-label">Cierre <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_hora_fin" class="form-input"
                       value="<?php echo e(old('fecha_hora_fin')); ?>" required>
                <?php $__errorArgs = ['fecha_hora_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="form-label">Instrucciones</label>
                <textarea name="instrucciones" rows="2" class="form-input text-sm"
                          placeholder="Indicaciones para los alumnos..."><?php echo e(old('instrucciones')); ?></textarea>
            </div>

            <button type="submit" class="btn-primary w-full">Agregar ventana</button>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/calendario/index.blade.php ENDPATH**/ ?>