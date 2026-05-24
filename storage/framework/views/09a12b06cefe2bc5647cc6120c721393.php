<?php $__env->startSection('title','Detalle de Ficha'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('financiero.fichas.index')); ?>">Fichas de Pago</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium"><?php echo e($ficha->folio_ficha); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('financiero.fichas.pdf', $ficha)); ?>" target="_blank" class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Descargar PDF
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 space-y-5">

        
        <div class="card">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs text-carbon-500 mb-1">Folio de Ficha</p>
                    <p class="text-2xl font-black font-mono text-navy-950"><?php echo e($ficha->folio_ficha); ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-carbon-500 mb-1">Estado</p>
                    <span class="badge text-sm px-3 py-1 <?php echo e($ficha->status_color); ?>"><?php echo e($ficha->status_nombre); ?></span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-carbon-500 mb-1">Monto</p>
                    <p class="text-3xl font-black text-navy-800"><?php echo e($ficha->monto_formateado); ?></p>
                </div>
            </div>

            
            <?php
            $pasos = ['pendiente' => 1, 'pagado' => 3, 'vencido' => 0, 'cancelado' => 0];
            $paso = $pasos[$ficha->status] ?? 0;
            ?>
            <?php if(in_array($ficha->status, ['pendiente', 'pagado'])): ?>
            <div class="mt-5">
                <div class="flex items-center gap-2">
                    <?php $__currentLoopData = ['Generada','Vigente','Pagada']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-1">
                        <div class="h-1.5 rounded-full <?php echo e($i < $paso ? 'bg-navy-800' : ($i === $paso - 1 ? 'bg-navy-800' : 'bg-carbon-200')); ?>"></div>
                        <p class="text-xs text-carbon-400 mt-1 text-center"><?php echo e($label); ?></p>
                    </div>
                    <?php if($i < 2): ?><div class="w-4 h-px bg-carbon-200 flex-shrink-0 mb-4"></div><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="card">
            <div class="card-header"><h3 class="card-title">Información de Pago</h3></div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <?php $__currentLoopData = [
                    ['Concepto',          $ficha->concepto],
                    ['Fecha de emisión',   $ficha->fecha_emision->format('d/m/Y')],
                    ['Fecha vencimiento',  $ficha->fecha_vencimiento->format('d/m/Y')],
                    ['Fecha de pago',      $ficha->fecha_pago?->format('d/m/Y H:i') ?? '—'],
                    ['Método de pago',     $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—'],
                    ['Referencia',         $ficha->referencia_pago ?? '—'],
                    ['Gateway Order ID',   $ficha->gateway_order_id ?? '—'],
                    ['Generado por',       $ficha->generadoPor?->name ?? 'Sistema'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <p class="text-carbon-500 text-xs mb-0.5"><?php echo e($label); ?></p>
                    <p class="font-medium text-carbon-900 break-all"><?php echo e($val); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($ficha->status === 'pendiente'): ?>
        <div class="card border-2 border-dashed border-amber-300 bg-amber-50/50" x-data="modal()">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-carbon-900">Registrar Pago Manual</h3>
                    <p class="text-sm text-carbon-500">Efectivo, transferencia u otro método fuera de pasarela.</p>
                </div>
                <button @click="show()" class="btn-warning btn-sm">Registrar pago</button>
            </div>

            
            <div x-show="open" x-transition class="modal-backdrop" @click.self="hide()">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="font-semibold">Registrar Pago Manual</h3>
                        <button @click="hide()" class="text-carbon-400 hover:text-carbon-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form method="POST" action="<?php echo e(route('financiero.fichas.pago-manual', $ficha)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <div class="modal-body space-y-4">
                            <div>
                                <label class="form-label">Método de pago <span class="text-danger">*</span></label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="transferencia">Transferencia bancaria</option>
                                    <option value="efectivo">Efectivo en caja</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Número de referencia</label>
                                <input name="referencia" class="form-input" placeholder="Folio, número de transferencia…">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="hide()" class="btn-secondary">Cancelar</button>
                            <button type="submit" class="btn-success">Confirmar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php endif; ?>

        
        <?php if(in_array($ficha->status, ['pendiente', 'vencido'])): ?>
        <div class="flex justify-end">
            <form method="POST" action="<?php echo e(route('financiero.fichas.cancelar', $ficha)); ?>" onsubmit="return confirm('¿Cancelar esta ficha?')">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button class="btn-danger btn-sm">Cancelar Ficha</button>
            </form>
        </div>
        <?php endif; ?>

        
        <?php if($ficha->comprobantes->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Comprobantes de Transferencia</h3>
                <span class="badge badge-info"><?php echo e($ficha->comprobantes->where('status', 'pendiente')->count()); ?> pendiente(s)</span>
            </div>
            <div class="space-y-3">
                <?php $__currentLoopData = $ficha->comprobantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start justify-between p-3 rounded-lg border border-carbon-100 bg-carbon-50">
                    <div class="flex-1 min-w-0 mr-4">
                        <a href="<?php echo e($comp->url); ?>" target="_blank"
                           class="text-sm font-medium hover:underline truncate block"
                           style="color: #5b35c0">
                            <?php echo e($comp->nombre_original ?? 'Ver comprobante'); ?>

                        </a>
                        <p class="text-xs text-carbon-400 mt-0.5">
                            Enviado el <?php echo e($comp->created_at->format('d/m/Y H:i')); ?>

                        </p>
                        <?php if($comp->observaciones && $comp->status === 'rechazado'): ?>
                        <p class="text-xs text-red-500 mt-1">Motivo: <?php echo e($comp->observaciones); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="badge <?php echo e($comp->status_color); ?>"><?php echo e($comp->status_nombre); ?></span>
                        <?php if($comp->status === 'pendiente'): ?>
                        <div x-data="{ open: false }">
                            <div class="flex gap-1">
                                <form method="POST" action="<?php echo e(route('financiero.comprobantes.aprobar', $comp)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn-success btn-sm"
                                            onclick="return confirm('¿Aprobar y marcar ficha como pagada?')">
                                        Aprobar
                                    </button>
                                </form>
                                <button @click="open = !open" class="btn-danger btn-sm">Rechazar</button>
                            </div>

                            <div x-show="open" x-cloak
                                 class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
                                 @keydown.escape.window="open = false">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl" @click.stop>
                                    <h3 class="font-semibold text-carbon-900 mb-3">Rechazar comprobante</h3>
                                    <form method="POST"
                                          action="<?php echo e(route('financiero.comprobantes.rechazar', $comp)); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Motivo <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="observaciones" rows="3" class="form-input"
                                                      placeholder="Motivo para el aspirante…" required></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="open = false" class="btn-secondary btn-sm">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn-danger btn-sm">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($ficha->transacciones->count()): ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Historial de Transacciones</h3></div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr><th>Gateway</th><th>Referencia</th><th>Monto</th><th>Estado</th><th>Fecha</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ficha->transacciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($txn->gateway_nombre); ?></td>
                            <td class="font-mono text-xs"><?php echo e($txn->referencia_externa); ?></td>
                            <td>$<?php echo e(number_format($txn->monto, 2)); ?></td>
                            <td><span class="badge-<?php echo e(['exitosa'=>'success','fallida'=>'danger','pendiente'=>'warning','reembolsada'=>'info'][$txn->status] ?? 'neutral'); ?>"><?php echo e($txn->status_nombre); ?></span></td>
                            <td class="text-xs text-carbon-400"><?php echo e($txn->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="space-y-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Aspirante</h3></div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-navy-800 font-bold text-sm"><?php echo e(strtoupper(substr($ficha->aspirante->nombre, 0, 1) . substr($ficha->aspirante->apellido_paterno, 0, 1))); ?></span>
                    </div>
                    <div>
                        <p class="font-semibold text-carbon-900"><?php echo e($ficha->aspirante->nombre_completo); ?></p>
                        <p class="text-xs text-carbon-400 font-mono"><?php echo e($ficha->aspirante->folio); ?></p>
                    </div>
                </div>
                <?php $__currentLoopData = [
                    ['Email',   $ficha->aspirante->email],
                    ['Tel.',    $ficha->aspirante->telefono ?? '—'],
                    ['CURP',    $ficha->aspirante->curp ?? '—'],
                    ['Carrera', $ficha->aspirante->carrera?->nombre ?? '—'],
                    ['Período', $ficha->aspirante->periodo?->nombre ?? '—'],
                    ['Estatus', $ficha->aspirante->status_nombre],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$l, $v]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between py-1.5 border-b border-carbon-100 last:border-0">
                    <span class="text-carbon-500"><?php echo e($l); ?></span>
                    <span class="font-medium text-right max-w-[55%] break-all"><?php echo e($v); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('financiero.aspirantes.show', $ficha->aspirante)); ?>"
                   class="btn-outline btn-sm w-full justify-center mt-2">
                    Ver perfil del aspirante
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.financiero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/financiero/fichas/show.blade.php ENDPATH**/ ?>