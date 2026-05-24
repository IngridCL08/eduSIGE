<?php $__env->startSection('title', $aspirante->nombre_completo); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('escolar.aspirantes.index')); ?>">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium"><?php echo e($aspirante->folio); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('escolar.aspirantes.edit', $aspirante)); ?>" class="btn-secondary btn-sm">Editar</a>
    <?php if($aspirante->status === \App\Models\Aspirante::STATUS_ADMITIDO && ! $aspirante->alumno): ?>
    <form method="POST" action="<?php echo e(route('escolar.inscripcion.store', $aspirante)); ?>"
          onsubmit="return confirm('¿Inscribir a <?php echo e($aspirante->nombre_completo); ?>? Se generará su matrícula y contraseña.')">
        <?php echo csrf_field(); ?>
        <button class="btn-success btn-sm">Inscribir como Alumno</button>
    </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('password_inicial')): ?>
<div class="bg-green-50 border border-green-300 rounded-xl p-5 mb-6">
    <p class="font-semibold text-green-800 mb-2">✓ Aspirante registrado — Credenciales de acceso al portal</p>
    <div class="bg-white rounded-lg border border-green-200 p-4 font-mono text-sm space-y-1">
        <p><span class="text-slate-500">Folio:</span>
           <span class="font-bold text-slate-900"><?php echo e($aspirante->folio); ?></span></p>
        <p><span class="text-slate-500">Email:</span>
           <span class="font-bold text-slate-900"><?php echo e($aspirante->email); ?></span></p>
        <p><span class="text-slate-500">Contraseña inicial:</span>
           <span class="font-bold text-slate-900"><?php echo e(session('password_inicial')); ?></span></p>
    </div>
    <p class="text-xs text-green-600 mt-2">⚠ Anota estas credenciales. No se volverán a mostrar.</p>
</div>
<?php endif; ?>


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
                <h3 class="card-title">Datos Personales</h3>
                <span class="badge <?php echo e($aspirante->status_color); ?>"><?php echo e($aspirante->status_nombre); ?></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <?php $__currentLoopData = [
                    ['Folio',         $aspirante->folio],
                    ['Nombre completo',$aspirante->nombre_completo],
                    ['CURP',          $aspirante->curp ?? '—'],
                    ['Sexo',          $aspirante->sexo === 'M' ? 'Masculino' : ($aspirante->sexo === 'F' ? 'Femenino' : 'Otro')],
                    ['Nacimiento',    $aspirante->fecha_nacimiento?->format('d/m/Y') ?? '—'],
                    ['Email',         $aspirante->email],
                    ['Teléfono',      $aspirante->telefono ?? '—'],
                    ['Domicilio',     $aspirante->domicilio ?? '—'],
                    ['Bachillerato',  $aspirante->bachillerato ?? '—'],
                    ['Promedio Bach.',($aspirante->promedio_bachillerato ? number_format($aspirante->promedio_bachillerato,2) : '—')],
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
                <h3 class="card-title">Documentos</h3>
                <a href="<?php echo e(route('escolar.documentos.index', ['aspirante_id' => $aspirante->id])); ?>"
                   class="btn-outline btn-sm">Gestionar</a>
            </div>
            <?php if($aspirante->documentos->count()): ?>
            <div class="space-y-2">
                <?php $__currentLoopData = $aspirante->documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                    <div class="flex items-center gap-2">
                        <?php if($doc->verificado): ?>
                        <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <?php else: ?>
                        <span class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <?php endif; ?>
                        <div>
                            <p class="text-sm font-medium text-carbon-800"><?php echo e($doc->tipo_nombre); ?></p>
                            <p class="text-xs text-carbon-400"><?php echo e($doc->nombre_archivo); ?></p>
                        </div>
                    </div>
                    <span class="text-xs <?php echo e($doc->verificado ? 'text-green-600' : 'text-amber-600'); ?>">
                        <?php echo e($doc->verificado ? 'Verificado' : 'Pendiente'); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <p class="text-sm text-carbon-400 py-4 text-center">Sin documentos cargados.</p>
            <?php endif; ?>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial de Cambios</h3>
                <a href="<?php echo e(route('escolar.aspirantes.historial', $aspirante)); ?>"
                   class="btn-outline btn-sm">Ver completo</a>
            </div>
            <p class="text-sm text-carbon-500">Registro de cambios de estado del aspirante.</p>
        </div>

    </div>

    
    <div class="space-y-5">

        
        <div class="card">
            <div class="card-header"><h3 class="card-title">Información Académica</h3></div>
            <div class="space-y-3">
                <?php $__currentLoopData = [
                    ['Carrera',  $aspirante->carrera?->nombre ?? '—'],
                    ['Clave',    $aspirante->carrera?->clave ?? '—'],
                    ['Período',  $aspirante->periodo?->nombre ?? '—'],
                    ['Prom. Bach.', $aspirante->promedio_bachillerato ? number_format($aspirante->promedio_bachillerato, 2) : '—'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$l, $v]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between py-2 border-b border-carbon-100 last:border-0">
                    <span class="text-sm text-carbon-500"><?php echo e($l); ?></span>
                    <span class="font-medium text-sm text-right max-w-[55%]"><?php echo e($v); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header"><h3 class="card-title">Ficha de Pago</h3></div>
            <?php $ficha = $aspirante->fichas()->latest()->first(); ?>
            <?php if($ficha): ?>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Folio</span>
                    <span class="font-mono font-semibold"><?php echo e($ficha->folio_ficha); ?></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Estado</span>
                    <span class="badge <?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Monto</span>
                    <span class="font-bold"><?php echo e($ficha->monto_formateado); ?></span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-carbon-500">Vencimiento</span>
                    <span><?php echo e($ficha->fecha_vencimiento->format('d/m/Y')); ?></span>
                </div>
            </div>
            <?php else: ?>
            <p class="text-sm text-carbon-400 py-3 text-center">Sin ficha registrada.</p>
            <?php endif; ?>
        </div>

        
        <div class="card" x-data="{ open: false }">
            <div class="card-header">
                <h3 class="card-title">Cambiar Estatus</h3>
                <button @click="open = !open" class="btn-outline btn-sm">
                    <span x-text="open ? 'Cerrar' : 'Cambiar'"></span>
                </button>
            </div>
            <div x-show="open" x-transition>
                <form method="POST" action="<?php echo e(route('escolar.aspirantes.estatus', $aspirante)); ?>" class="space-y-3 mt-3">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div>
                        <label class="form-label">Nuevo Estatus <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            <?php $__currentLoopData = \App\Models\Aspirante::statuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($aspirante->status === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="form-textarea" placeholder="Motivo del cambio…"></textarea>
                    </div>
                    <button type="submit" class="btn-warning w-full justify-center">Actualizar Estatus</button>
                </form>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/aspirantes/show.blade.php ENDPATH**/ ?>