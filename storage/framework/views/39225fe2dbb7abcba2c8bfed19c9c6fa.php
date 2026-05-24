<?php $__env->startSection('title', 'Comprobantes de Transferencia'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Comprobantes</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert-success mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>


<form method="GET" class="card mb-5">
    <div class="flex gap-3 items-end flex-wrap">
        <div class="flex-1 min-w-[160px]">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" <?php echo e(request('status') === 'pendiente' ? 'selected' : ''); ?>>Pendientes</option>
                <option value="aprobado"  <?php echo e(request('status') === 'aprobado'  ? 'selected' : ''); ?>>Aprobados</option>
                <option value="rechazado" <?php echo e(request('status') === 'rechazado' ? 'selected' : ''); ?>>Rechazados</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Filtrar</button>
            <a href="<?php echo e(route('financiero.comprobantes.index')); ?>" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Aspirante</th>
                    <th>Folio Ficha</th>
                    <th>Comprobante</th>
                    <th>Fecha envío</th>
                    <th>Estado</th>
                    <th>Revisado por</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $comprobantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="font-medium text-carbon-900">
                            <?php echo e($comp->fichaPago?->aspirante?->nombre_completo ?? '—'); ?>

                        </div>
                        <div class="text-xs text-carbon-400 font-mono">
                            <?php echo e($comp->fichaPago?->aspirante?->folio ?? ''); ?>

                        </div>
                    </td>
                    <td class="font-mono text-xs"><?php echo e($comp->fichaPago?->folio_ficha ?? '—'); ?></td>
                    <td>
                        <a href="<?php echo e($comp->url); ?>" target="_blank"
                           class="text-sm hover:underline truncate max-w-[180px] block"
                           style="color: #5b35c0">
                            <?php echo e($comp->nombre_original ?? 'Ver archivo'); ?>

                        </a>
                        <?php if($comp->observaciones && $comp->status === 'rechazado'): ?>
                        <p class="text-xs text-red-500 mt-0.5"><?php echo e($comp->observaciones); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="text-sm whitespace-nowrap text-carbon-500">
                        <?php echo e($comp->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td><span class="badge <?php echo e($comp->status_color); ?>"><?php echo e($comp->status_nombre); ?></span></td>
                    <td class="text-sm text-carbon-500">
                        <?php echo e($comp->revisadoPor?->name ?? '—'); ?>

                        <?php if($comp->revisado_at): ?>
                        <div class="text-xs text-carbon-400"><?php echo e($comp->revisado_at->format('d/m/Y')); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if($comp->status === 'pendiente'): ?>
                        <div class="flex justify-end gap-2" x-data="{ open: false }">
                            <form method="POST" action="<?php echo e(route('financiero.comprobantes.aprobar', $comp)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn-success btn-sm"
                                        onclick="return confirm('¿Aprobar este comprobante y marcar la ficha como pagada?')">
                                    Aprobar
                                </button>
                            </form>
                            <button @click="open = !open" class="btn-danger btn-sm">Rechazar</button>

                            
                            <div x-show="open" x-cloak
                                 class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
                                 @keydown.escape.window="open = false">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl" @click.stop>
                                    <h3 class="font-semibold text-carbon-900 mb-4">Rechazar comprobante</h3>
                                    <p class="text-sm text-carbon-500 mb-4">
                                        El aspirante verá este motivo en su portal y podrá subir un nuevo comprobante.
                                    </p>
                                    <form method="POST"
                                          action="<?php echo e(route('financiero.comprobantes.rechazar', $comp)); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Motivo de rechazo <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="observaciones" rows="3"
                                                      class="form-input"
                                                      placeholder="Ej. El comprobante no corresponde al monto indicado…"
                                                      required></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="open = false"
                                                    class="btn-secondary btn-sm">Cancelar</button>
                                            <button type="submit" class="btn-danger btn-sm">
                                                Confirmar rechazo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <span class="text-xs text-carbon-400">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-10 text-carbon-400">
                        No hay comprobantes con los filtros aplicados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($comprobantes->hasPages()): ?>
    <div class="px-4 py-3 border-t border-carbon-100"><?php echo e($comprobantes->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/comprobantes/index.blade.php ENDPATH**/ ?>