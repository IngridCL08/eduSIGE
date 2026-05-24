<?php $__env->startSection('title','Historial Académico — ' . $alumno->matricula); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('escolar.alumnos.index')); ?>">Alumnos</a>
    <span class="breadcrumb-separator">/</span>
    <a href="<?php echo e(route('escolar.alumnos.show', $alumno)); ?>"><?php echo e($alumno->matricula); ?></a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Historial Académico</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <button x-data @click="$dispatch('open-modal')" class="btn-primary btn-sm">+ Agregar Materia</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ modal: false }" @open-modal.window="modal = true">


<div class="card mb-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-navy-800 font-black">
                    <?php echo e(strtoupper(substr($alumno->aspirante->nombre, 0, 1) . substr($alumno->aspirante->apellido_paterno, 0, 1))); ?>

                </span>
            </div>
            <div>
                <p class="font-bold text-carbon-900"><?php echo e($alumno->aspirante->nombre_completo); ?></p>
                <p class="text-xs text-carbon-400 font-mono"><?php echo e($alumno->matricula); ?> · <?php echo e($alumno->carrera?->clave); ?></p>
            </div>
        </div>
        <div class="flex gap-6">
            <div class="text-center">
                <p class="text-2xl font-black text-navy-800">
                    <?php echo e($alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—'); ?>

                </p>
                <p class="text-xs text-carbon-400">Promedio</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-green-700"><?php echo e($alumno->creditos_acumulados ?? 0); ?></p>
                <p class="text-xs text-carbon-400">Créditos</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-carbon-900"><?php echo e($historial->count()); ?></p>
                <p class="text-xs text-carbon-400">Materias</p>
            </div>
        </div>
    </div>
</div>


<div class="card p-0 overflow-hidden">
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
                <?php $__empty_1 = true; $__currentLoopData = $historial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $hClasses = ['cursando'=>'badge-warning','acreditada'=>'badge-success','reprobada'=>'badge-danger','baja'=>'badge-neutral'];
                    $hLabels  = ['cursando'=>'Cursando','acreditada'=>'Acreditada','reprobada'=>'Reprobada','baja'=>'Baja'];
                ?>
                <tr>
                    <td class="font-mono text-xs"><?php echo e($h->clave_materia ?? '—'); ?></td>
                    <td class="font-medium"><?php echo e($h->materia); ?></td>
                    <td><?php echo e($h->creditos); ?></td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap"><?php echo e($h->periodo?->nombre ?? '—'); ?></td>
                    <td>
                        <?php if($h->calificacion !== null): ?>
                        <span class="font-bold text-base <?php echo e($h->calificacion >= 6 ? 'text-green-700' : 'text-red-600'); ?>">
                            <?php echo e(number_format($h->calificacion, 1)); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-carbon-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="<?php echo e($hClasses[$h->status] ?? 'badge-neutral'); ?> badge">
                            <?php echo e($hLabels[$h->status] ?? $h->status); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-10 text-carbon-400">
                        No hay materias registradas en el historial académico.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div x-show="modal" x-transition class="modal-backdrop" @click.self="modal = false">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="font-semibold">Agregar Materia al Historial</h3>
            <button @click="modal = false" class="text-carbon-400 hover:text-carbon-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="<?php echo e(route('escolar.alumnos.historial.store', $alumno)); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Clave</label>
                        <input name="clave_materia" class="form-input" placeholder="ISIA-101">
                    </div>
                    <div>
                        <label class="form-label">Créditos <span class="text-danger">*</span></label>
                        <input type="number" name="creditos" min="1" max="20" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Nombre de la Materia <span class="text-danger">*</span></label>
                    <input name="materia" class="form-input" required placeholder="Cálculo Diferencial e Integral">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Período <span class="text-danger">*</span></label>
                        <select name="periodo_id" class="form-select" required>
                            <?php $__currentLoopData = $periodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Calificación</label>
                        <input type="number" name="calificacion" step="0.1" min="0" max="10" class="form-input" placeholder="0 – 10">
                    </div>
                </div>
                <div>
                    <label class="form-label">Estatus <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="cursando">Cursando</option>
                        <option value="acreditada">Acreditada</option>
                        <option value="reprobada">Reprobada</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="modal = false" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Agregar Materia</button>
            </div>
        </form>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.escolar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/escolar/alumnos/historial.blade.php ENDPATH**/ ?>