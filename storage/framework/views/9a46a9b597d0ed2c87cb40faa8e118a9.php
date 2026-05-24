<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-6">Mi Perfil</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-4 text-sm uppercase tracking-wider">Datos personales</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Nombre completo</dt>
                <dd class="font-medium text-slate-800 text-right"><?php echo e($aspirante->nombre_completo); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Folio</dt>
                <dd class="font-mono font-medium text-slate-800"><?php echo e($aspirante->folio); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">CURP</dt>
                <dd class="font-mono text-slate-800"><?php echo e($aspirante->curp ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Fecha de nacimiento</dt>
                <dd class="text-slate-800"><?php echo e($aspirante->fecha_nacimiento?->format('d/m/Y') ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Sexo</dt>
                <dd class="text-slate-800"><?php echo e($aspirante->sexo_nombre); ?></dd>
            </div>
        </dl>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-4 text-sm uppercase tracking-wider">Contacto</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Correo electrónico</dt>
                <dd class="font-medium text-slate-800 break-all"><?php echo e($aspirante->email); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Teléfono</dt>
                <dd class="text-slate-800"><?php echo e($aspirante->telefono ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Domicilio</dt>
                <dd class="text-slate-800 text-right max-w-[200px]"><?php echo e($aspirante->domicilio); ?></dd>
            </div>
        </dl>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-4 text-sm uppercase tracking-wider">Datos académicos</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Bachillerato</dt>
                <dd class="text-slate-800 text-right max-w-[200px]"><?php echo e($aspirante->bachillerato ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Promedio</dt>
                <dd class="font-medium text-slate-800"><?php echo e($aspirante->promedio_bachillerato ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Año de egreso</dt>
                <dd class="text-slate-800"><?php echo e($aspirante->anio_egreso ?? '—'); ?></dd>
            </div>
        </dl>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-4 text-sm uppercase tracking-wider">Solicitud de ingreso</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Carrera</dt>
                <dd class="font-medium text-slate-800 text-right max-w-[200px]"><?php echo e($aspirante->carrera?->nombre ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Período</dt>
                <dd class="text-slate-800"><?php echo e($aspirante->periodo?->nombre ?? '—'); ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Estatus</dt>
                <dd><span class="badge <?php echo e($aspirante->status_color); ?>"><?php echo e($aspirante->status_nombre); ?></span></dd>
            </div>
        </dl>
    </div>

</div>

<p class="text-xs text-slate-400 mt-4">
    Para actualizar tus datos personales, contacta a Control Escolar.
</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-aspirante', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/aspirante/perfil.blade.php ENDPATH**/ ?>