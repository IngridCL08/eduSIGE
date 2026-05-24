<?php $__env->startSection('title', 'Inicio'); ?>

<?php $__env->startSection('content'); ?>

<h1 class="text-xl font-bold text-slate-800 mb-1">Bienvenido, <?php echo e($alumno->aspirante?->nombre ?? $alumno->matricula); ?></h1>
<p class="text-sm text-slate-500 mb-6">Matrícula: <span class="font-mono font-semibold"><?php echo e($alumno->matricula); ?></span></p>


<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800"><?php echo e($alumno->promedio_general ?? '—'); ?></p>
        <p class="text-xs text-slate-400 mt-1">Promedio general</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800"><?php echo e($alumno->creditos_acumulados); ?></p>
        <p class="text-xs text-slate-400 mt-1">Créditos acumulados</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800"><?php echo e($avanceCreditos); ?>%</p>
        <p class="text-xs text-slate-400 mt-1">Avance de carrera</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <span class="badge <?php echo e($alumno->status_color); ?>"><?php echo e($alumno->status_nombre); ?></span>
        <p class="text-xs text-slate-400 mt-2">Estatus</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Mi Carrera</h3>
        <p class="font-bold text-slate-800 text-base"><?php echo e($alumno->carrera?->nombre ?? '—'); ?></p>
        <p class="text-sm text-slate-500 mt-1">Clave: <span class="font-mono"><?php echo e($alumno->carrera?->clave ?? '—'); ?></span></p>
        <?php if($alumno->carrera?->creditos_totales): ?>
        <div class="mt-3">
            <div class="flex justify-between text-xs text-slate-400 mb-1">
                <span>Créditos</span>
                <span><?php echo e($alumno->creditos_acumulados); ?> / <?php echo e($alumno->carrera->creditos_totales); ?></span>
            </div>
            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: <?php echo e($avanceCreditos); ?>%"></div>
            </div>
        </div>
        <?php endif; ?>
        <p class="text-xs text-slate-400 mt-3">Período de ingreso: <?php echo e($alumno->periodoIngreso?->nombre ?? '—'); ?></p>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Últimas materias</h3>
        <?php $__empty_1 = true; $__currentLoopData = $alumno->historial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0 text-sm">
            <div>
                <p class="font-medium text-slate-700"><?php echo e($h->materia); ?></p>
                <p class="text-xs text-slate-400"><?php echo e($h->periodo?->nombre ?? '—'); ?></p>
            </div>
            <span class="font-bold text-slate-800"><?php echo e($h->calificacion ?? '—'); ?></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-slate-400">Sin materias registradas aún.</p>
        <?php endif; ?>
        <a href="<?php echo e(route('portal.alumno.historial')); ?>"
           class="text-emerald-600 text-xs font-medium mt-3 block hover:underline">
            Ver historial completo →
        </a>
    </div>

</div>


<?php if($alumno->adeudos->isNotEmpty()): ?>
<div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-4">
    <div class="flex items-start justify-between">
        <div>
            <p class="font-semibold text-amber-800 text-sm">Tienes adeudos pendientes</p>
            <p class="text-xs text-amber-600 mt-0.5"><?php echo e($alumno->adeudos->count()); ?> concepto(s) sin pagar</p>
        </div>
        <a href="<?php echo e(route('portal.alumno.adeudos')); ?>" class="btn-sm text-amber-700 border border-amber-300 bg-white hover:bg-amber-50 rounded-lg px-3 py-1.5 text-xs font-medium">
            Ver adeudos →
        </a>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/dashboard.blade.php ENDPATH**/ ?>