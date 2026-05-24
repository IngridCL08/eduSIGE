<?php $__env->startSection('title','Alumno — ' . $alumno->matricula); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('escolar.alumnos.index')); ?>">Alumnos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium"><?php echo e($alumno->matricula); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.alumnos.edit', $alumno)); ?>" class="btn-secondary btn-sm">Editar</a>
    <a href="<?php echo e(route('escolar.alumnos.historial', $alumno)); ?>" class="btn-outline btn-sm">Historial Académico</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('inscripcion_exitosa')): ?>
<?php $datos = session('inscripcion_exitosa'); ?>
<div class="bg-blue-50 border border-blue-300 rounded-xl p-5 mb-6">
    <p class="font-semibold text-blue-800 mb-2">✓ Inscripción completada — Credenciales del Portal Alumno</p>
    <div class="bg-white rounded-lg border border-blue-200 p-4 font-mono text-sm space-y-1">
        <p><span class="text-slate-500">Nombre:</span>
           <span class="font-bold text-slate-900"><?php echo e($datos['nombre']); ?></span></p>
        <p><span class="text-slate-500">Matrícula:</span>
           <span class="font-bold text-slate-900"><?php echo e($datos['matricula']); ?></span></p>
        <p><span class="text-slate-500">Contraseña inicial:</span>
           <span class="font-bold text-slate-900"><?php echo e($datos['password']); ?></span></p>
    </div>
    <p class="text-xs text-blue-600 mt-2">⚠ Anota estas credenciales. No se volverán a mostrar.</p>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Alumno</h3>
                <?php
                    $statusClasses = ['activo'=>'badge-success','baja_temporal'=>'badge-warning','baja_definitiva'=>'badge-danger','egresado'=>'badge-info','titulado'=>'badge-neutral'];
                    $statusLabels  = ['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado'];
                ?>
                <span class="<?php echo e($statusClasses[$alumno->status] ?? 'badge-neutral'); ?> badge">
                    <?php echo e($statusLabels[$alumno->status] ?? $alumno->status); ?>

                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <?php $__currentLoopData = [
                    ['Matrícula',     $alumno->matricula],
                    ['Nombre',        $alumno->aspirante->nombre_completo],
                    ['CURP',          $alumno->aspirante->curp ?? '—'],
                    ['Email',         $alumno->aspirante->email],
                    ['Teléfono',      $alumno->aspirante->telefono ?? '—'],
                    ['Sexo',          $alumno->aspirante->sexo === 'M' ? 'Masculino' : ($alumno->aspirante->sexo === 'F' ? 'Femenino' : 'Otro')],
                    ['Carrera',       $alumno->carrera?->nombre ?? '—'],
                    ['Período ingreso',$alumno->periodoIngreso?->nombre ?? '—'],
                    ['Promedio',      $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—'],
                    ['Créditos',      ($alumno->creditos_acumulados ?? 0) . ' créditos'],
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
                <h3 class="card-title">Historial Académico</h3>
                <a href="<?php echo e(route('escolar.alumnos.historial', $alumno)); ?>" class="btn-outline btn-sm">Ver completo</a>
            </div>
            <?php if($alumno->historial->count()): ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Materia</th>
                            <th>Créditos</th>
                            <th>Período</th>
                            <th>Calificación</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $alumno->historial()->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $hClasses = ['cursando'=>'badge-warning','acreditada'=>'badge-success','reprobada'=>'badge-danger','baja'=>'badge-neutral'];
                            $hLabels  = ['cursando'=>'Cursando','acreditada'=>'Acreditada','reprobada'=>'Reprobada','baja'=>'Baja'];
                        ?>
                        <tr>
                            <td class="font-mono text-xs"><?php echo e($h->clave_materia ?? '—'); ?></td>
                            <td class="font-medium text-sm"><?php echo e($h->materia); ?></td>
                            <td><?php echo e($h->creditos); ?></td>
                            <td class="text-xs text-carbon-500"><?php echo e($h->periodo?->nombre ?? '—'); ?></td>
                            <td class="font-bold <?php echo e($h->calificacion >= 6 ? 'text-green-700' : 'text-red-600'); ?>">
                                <?php echo e($h->calificacion !== null ? number_format($h->calificacion, 1) : '—'); ?>

                            </td>
                            <td>
                                <span class="<?php echo e($hClasses[$h->status] ?? 'badge-neutral'); ?> badge">
                                    <?php echo e($hLabels[$h->status] ?? $h->status); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-sm text-carbon-400 py-6 text-center">Sin materias registradas en el historial.</p>
            <?php endif; ?>
        </div>

    </div>

    
    <div class="space-y-5">

        
        <div class="card">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-navy-800 font-black text-lg">
                        <?php echo e(strtoupper(substr($alumno->aspirante->nombre, 0, 1) . substr($alumno->aspirante->apellido_paterno, 0, 1))); ?>

                    </span>
                </div>
                <div>
                    <p class="font-bold text-carbon-900"><?php echo e($alumno->aspirante->nombre_completo); ?></p>
                    <p class="font-mono text-xs text-carbon-400"><?php echo e($alumno->matricula); ?></p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-navy-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-black text-navy-800">
                        <?php echo e($alumno->promedio_general ? number_format($alumno->promedio_general, 1) : '—'); ?>

                    </p>
                    <p class="text-xs text-navy-600 mt-0.5">Promedio</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-black text-green-800"><?php echo e($alumno->creditos_acumulados ?? 0); ?></p>
                    <p class="text-xs text-green-600 mt-0.5">Créditos</p>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Documentos</h3>
                <a href="<?php echo e(route('escolar.documentos.index', ['aspirante_id' => $alumno->aspirante_id])); ?>"
                   class="btn-outline btn-sm">Gestionar</a>
            </div>
            <?php
                $docs = $alumno->aspirante->documentos;
                $verificados = $docs->where('verificado', true)->count();
            ?>
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-carbon-100 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full"
                         style="width: <?php echo e($docs->count() ? round($verificados/$docs->count()*100) : 0); ?>%"></div>
                </div>
                <span class="text-sm font-medium"><?php echo e($verificados); ?>/<?php echo e($docs->count()); ?></span>
            </div>
            <p class="text-xs text-carbon-400 mt-1">Documentos verificados</p>
        </div>

        
        <div class="card" x-data="{ open: false }">
            <div class="card-header">
                <h3 class="card-title">Cambiar Estatus</h3>
                <button @click="open = !open" class="btn-outline btn-sm">
                    <span x-text="open ? 'Cerrar' : 'Cambiar'"></span>
                </button>
            </div>
            <div x-show="open" x-transition>
                <form method="POST" action="<?php echo e(route('escolar.alumnos.estatus', $alumno)); ?>" class="space-y-3 mt-3">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div>
                        <label class="form-label">Nuevo Estatus <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <?php $__currentLoopData = ['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e($alumno->status === $k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="form-textarea"></textarea>
                    </div>
                    <button type="submit" class="btn-warning w-full justify-center">Actualizar Estatus</button>
                </form>
            </div>
        </div>

        
        <a href="<?php echo e(route('escolar.aspirantes.show', $alumno->aspirante)); ?>"
           class="btn-outline w-full justify-center">Ver Expediente de Aspirante</a>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/alumnos/show.blade.php ENDPATH**/ ?>