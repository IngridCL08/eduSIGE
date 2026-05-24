<?php $__env->startSection('title', 'Mis Documentos'); ?>

<?php $__env->startSection('content'); ?>

<h2 class="text-xl font-bold text-slate-800 mb-6">Mis Documentos</h2>

<?php
    $verificados = $documentos->where('verificado', true)->count();
    $total       = count($tipos);
?>

<div class="bg-white rounded-xl border border-slate-200 p-5 mb-5">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-slate-600">Expediente completo</span>
        <span class="text-sm font-bold text-slate-800"><?php echo e($verificados); ?> / <?php echo e($total); ?> verificados</span>
    </div>
    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full bg-emerald-500 rounded-full transition-all"
             style="width: <?php echo e($total > 0 ? round($verificados / $total * 100) : 0); ?>%"></div>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">Documento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 hidden sm:table-cell">Archivo</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500">Estado</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clave => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $doc = $documentos->firstWhere('tipo', $clave); ?>
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium text-slate-700"><?php echo e($nombre); ?></td>
                <td class="px-4 py-3 text-slate-500 hidden sm:table-cell text-xs truncate max-w-[160px]">
                    <?php echo e($doc?->nombre_archivo ?? '—'); ?>

                </td>
                <td class="px-4 py-3 text-center">
                    <?php if($doc): ?>
                        <span class="badge <?php echo e($doc->verificado ? 'badge-success' : 'badge-warning'); ?>">
                            <?php echo e($doc->verificado ? 'Verificado' : 'En revisión'); ?>

                        </span>
                    <?php else: ?>
                        <span class="badge badge-neutral">No entregado</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-center">
                    <?php if($doc): ?>
                    <a href="<?php echo e($doc->url); ?>" target="_blank"
                       class="text-emerald-600 text-xs hover:underline">Ver</a>
                    <?php else: ?>
                    <span class="text-slate-300 text-xs">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<p class="text-xs text-slate-400 mt-4">
    Para entregar o reemplazar documentos, acude a la ventanilla de Control Escolar.
</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal-alumno', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/portal/alumno/documentos.blade.php ENDPATH**/ ?>