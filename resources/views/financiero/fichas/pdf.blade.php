<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ficha de Pago — {{ $ficha->folio_ficha }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #0A0A0A; }

        .header { background: #0F172A; color: white; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center; }
        .header-logo { font-size: 22px; font-weight: 800; }
        .header-sub { font-size: 10px; color: #94a3b8; margin-top: 2px; }
        .header-right { text-align: right; }
        .header-folio { font-size: 13px; font-weight: 700; font-family: monospace; }

        .status-bar { padding: 8px 32px; font-size: 11px; font-weight: 600; text-align: center; }
        .status-pendiente { background: #fef3c7; color: #92400e; }
        .status-pagado    { background: #d1fae5; color: #065f46; }
        .status-vencido   { background: #fee2e2; color: #7f1d1d; }
        .status-cancelado { background: #f1f5f9; color: #475569; }

        .section { padding: 20px 32px; border-bottom: 1px solid #e2e8f0; }
        .section-title { font-size: 9px; font-weight: 700; text-transform: uppercase;
                         letter-spacing: 0.1em; color: #64748b; margin-bottom: 10px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
        .field-label { font-size: 9px; color: #64748b; margin-bottom: 2px; }
        .field-value { font-size: 11px; font-weight: 500; }

        .monto-box { margin: 20px 32px; padding: 16px 24px;
                     background: #0F172A; color: white; border-radius: 8px; text-align: center; }
        .monto-label { font-size: 10px; color: #94a3b8; margin-bottom: 4px; }
        .monto-value { font-size: 28px; font-weight: 800; }
        .monto-concepto { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        .footer { padding: 16px 32px; background: #f8fafc; text-align: center; font-size: 9px; color: #94a3b8; }
        .barcode { text-align: center; font-family: monospace; font-size: 14px;
                   letter-spacing: 4px; color: #1E3A5F; margin: 12px 0 4px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="header-logo">eduSIGE</div>
            <div class="header-sub">{{ config('app.edusige.institucion') }}</div>
            <div class="header-sub">Sistema Integral de Gestión Educativa</div>
        </div>
        <div class="header-right">
            <div class="header-sub">FICHA DE PAGO</div>
            <div class="header-folio">{{ $ficha->folio_ficha }}</div>
            <div class="header-sub">Emitida: {{ $ficha->fecha_emision->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Barra de estatus --}}
    <div class="status-bar status-{{ $ficha->status }}">
        Estado: {{ strtoupper($ficha->status_nombre) }}
        @if($ficha->status === 'pagado' && $ficha->fecha_pago)
            — Pagado el {{ $ficha->fecha_pago->format('d/m/Y H:i') }}
        @endif
    </div>

    {{-- Monto --}}
    <div class="monto-box">
        <div class="monto-label">MONTO A PAGAR</div>
        <div class="monto-value">${{ number_format($ficha->monto, 2) }} MXN</div>
        <div class="monto-concepto">{{ $ficha->concepto }}</div>
    </div>

    {{-- Datos del aspirante --}}
    <div class="section">
        <div class="section-title">Datos del Aspirante</div>
        <div class="grid-3">
            <div>
                <div class="field-label">Folio</div>
                <div class="field-value">{{ $ficha->aspirante->folio }}</div>
            </div>
            <div>
                <div class="field-label">Nombre Completo</div>
                <div class="field-value">{{ $ficha->aspirante->nombre_completo }}</div>
            </div>
            <div>
                <div class="field-label">CURP</div>
                <div class="field-value">{{ $ficha->aspirante->curp ?? '—' }}</div>
            </div>
            <div>
                <div class="field-label">Carrera Solicitada</div>
                <div class="field-value">{{ $ficha->aspirante->carrera?->nombre }}</div>
            </div>
            <div>
                <div class="field-label">Período</div>
                <div class="field-value">{{ $ficha->aspirante->periodo?->nombre }}</div>
            </div>
            <div>
                <div class="field-label">Correo Electrónico</div>
                <div class="field-value">{{ $ficha->aspirante->email }}</div>
            </div>
        </div>
    </div>

    {{-- Datos de la ficha --}}
    <div class="section">
        <div class="section-title">Información de Pago</div>
        <div class="grid-3">
            <div>
                <div class="field-label">Fecha de Emisión</div>
                <div class="field-value">{{ $ficha->fecha_emision->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="field-label">Fecha de Vencimiento</div>
                <div class="field-value">{{ $ficha->fecha_vencimiento->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="field-label">Método de Pago</div>
                <div class="field-value">{{ $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : 'Por definir' }}</div>
            </div>
            @if($ficha->referencia_pago)
            <div>
                <div class="field-label">Referencia de Pago</div>
                <div class="field-value">{{ $ficha->referencia_pago }}</div>
            </div>
            @endif
            <div>
                <div class="field-label">Generado por</div>
                <div class="field-value">{{ $ficha->generadoPor?->name ?? 'Sistema' }}</div>
            </div>
        </div>
    </div>

    {{-- Código de referencia --}}
    <div style="padding: 12px 32px; text-align: center; border-bottom: 1px solid #e2e8f0;">
        <div style="font-size: 9px; color: #64748b; margin-bottom: 4px;">NÚMERO DE REFERENCIA</div>
        <div class="barcode">{{ $ficha->folio_ficha }}</div>
        <div style="font-size: 9px; color: #94a3b8;">Presentar este documento al realizar el pago</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Este documento es un comprobante de ficha de pago emitido por el sistema eduSIGE.</p>
        <p>Vigencia: del {{ $ficha->fecha_emision->format('d/m/Y') }} al {{ $ficha->fecha_vencimiento->format('d/m/Y') }}</p>
        <p style="margin-top:4px;">Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

</body>
</html>
