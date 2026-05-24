<?php $__env->startSection('title', 'Historial Académico'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Historial Académico</h2>
    <?php if($promedioGeneral): ?>
    <div class="text-right">
        <p class="text-2xl font-bold text-emerald-600"><?php echo e(number_format($promedioGeneral, 2)); ?></p>
        <p class="text-xs text-slate-400">Promedio general</p>
    </div>
    <?php endif; ?>
</div>

<?php $__empty_1 = true; $__currentLoopData = $historial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo => $materias): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="mb-6">
    <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wider mb-3 px-1"><?php echo e($periodo); ?></h3>
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500">Materia</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Créditos</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Calificación</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Estatus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php $__currentLoopData = $materias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-700"><?php echo e($h->materia); ?></td>
                    <td class="px-4 py-3 text-center text-slate-500"><?php echo e($h->creditos ?? '—'); ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold <?php echo e(($h->calificacion ?? 0) >= 6 ? 'text-slate-800' : 'text-red-600'); ?>">
                            <?php echo e($h->calificacion !== null ? number_format($h->calificacion, 1) : '—'); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php
                            $color = match($h->status ?? '') {
                                'acreditada'     => 'badge-success',
                                'no_acreditada'  => 'badge-danger',
                                'en_curso'       => 'badge-info',
                                default          => 'badge-neutral',
                            };
                            $label = match($h->status ?? '') {
                                'acreditada'    => 'Acreditada',
                                'no_acreditada' => 'No acreditada',
                                'en_curso'      => 'En curso',
                                default         => $h->status ?? '—',
                            };
                        ?>
                        <span class="badge <?php echo e($color); ?>"><?php echo e($label); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-400">
    No hay materias registradas en tu historial aún.
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/historial.blade.php ENDPATH**/ ?>