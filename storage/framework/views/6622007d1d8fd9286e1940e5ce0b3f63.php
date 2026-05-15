<?php $__env->startSection('title','Documentos'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Documentos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-1">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Cargar Documento</h3></div>
            <form method="POST" action="<?php echo e(route('escolar.documentos.store')); ?>" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label">Aspirante <span class="text-danger">*</span></label>
                    <select name="aspirante_id" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        <?php $__currentLoopData = $aspirantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>" <?php echo e(request('aspirante_id') == $a->id ? 'selected' : ''); ?>>
                            <?php echo e($a->folio); ?> — <?php echo e($a->nombre_completo); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['aspirante_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        <?php $__currentLoopData = [
                            'acta_nacimiento'      => 'Acta de Nacimiento',
                            'certificado_bachillerato' => 'Certificado de Bachillerato',
                            'curp'                 => 'CURP',
                            'identificacion'       => 'Identificación Oficial',
                            'foto'                 => 'Fotografía',
                            'comprobante_domicilio'=> 'Comprobante de Domicilio',
                            'otro'                 => 'Otro',
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
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
                    <label class="form-label">Archivo <span class="text-danger">*</span></label>
                    <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png"
                           class="form-input <?php $__errorArgs = ['archivo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <p class="form-help">PDF, JPG o PNG — máx. 5 MB</p>
                    <?php $__errorArgs = ['archivo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Cargar Documento</button>
            </form>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-4">

        
        <form method="GET" class="card py-3">
            <div class="flex gap-3 flex-wrap">
                <div class="flex-1 min-w-40">
                    <select name="aspirante_id" class="form-select" onchange="this.form.submit()">
                        <option value="">— Todos los aspirantes —</option>
                        <?php $__currentLoopData = $aspirantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>" <?php echo e(request('aspirante_id') == $a->id ? 'selected' : ''); ?>>
                            <?php echo e($a->folio); ?> — <?php echo e($a->nombre_completo); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <select name="verificado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="1" <?php echo e(request('verificado') === '1' ? 'selected' : ''); ?>>Verificados</option>
                        <option value="0" <?php echo e(request('verificado') === '0' ? 'selected' : ''); ?>>Pendientes</option>
                    </select>
                </div>
                <a href="<?php echo e(route('escolar.documentos.index')); ?>" class="btn-secondary btn-sm self-center">Limpiar</a>
            </div>
        </form>

        
        <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card">
            <div class="flex items-start gap-4">
                
                <div class="w-10 h-10 bg-navy-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-carbon-900 text-sm"><?php echo e($doc->tipo_nombre); ?></p>
                            <p class="text-xs text-carbon-400 mt-0.5"><?php echo e($doc->aspirante->folio); ?> — <?php echo e($doc->aspirante->nombre_completo); ?></p>
                        </div>
                        <?php if($doc->verificado): ?>
                        <span class="badge-success badge flex-shrink-0">Verificado</span>
                        <?php else: ?>
                        <span class="badge-warning badge flex-shrink-0">Pendiente</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-xs text-carbon-500 mt-1 truncate"><?php echo e($doc->nombre_archivo); ?></p>

                    <?php if($doc->verificado): ?>
                    <p class="text-xs text-green-600 mt-1">
                        Verificado por <?php echo e($doc->verificadoPor?->name ?? '—'); ?>

                        <?php echo e($doc->verificado_at ? '· ' . $doc->verificado_at->format('d/m/Y') : ''); ?>

                    </p>
                    <?php endif; ?>

                    <div class="flex items-center gap-2 mt-3">
                        <a href="<?php echo e(Storage::url($doc->ruta_archivo)); ?>" target="_blank"
                           class="btn-outline btn-sm">Ver archivo</a>

                        <?php if(!$doc->verificado): ?>
                        <form method="POST" action="<?php echo e(route('escolar.documentos.verificar', $doc)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="btn-success btn-sm">Verificar</button>
                        </form>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('escolar.documentos.destroy', $doc)); ?>"
                              onsubmit="return confirm('¿Eliminar este documento?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card py-16 text-center text-carbon-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-carbon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">No hay documentos cargados.</p>
        </div>
        <?php endif; ?>

        <?php if(isset($documentos) && method_exists($documentos, 'hasPages') && $documentos->hasPages()): ?>
        <div><?php echo e($documentos->links()); ?></div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/documentos/index.blade.php ENDPATH**/ ?>