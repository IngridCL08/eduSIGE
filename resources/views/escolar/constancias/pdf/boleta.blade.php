<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a; padding: 36px 48px; }
  .header { text-align: center; border-bottom: 2px solid #2d1590; padding-bottom: 14px; margin-bottom: 20px; }
  .header h1 { font-size: 12pt; font-weight: bold; color: #1a0960; text-transform: uppercase; }
  .header h2 { font-size: 10pt; color: #4219bf; margin-top: 3px; }
  .doc-title { text-align: center; margin: 16px 0; }
  .doc-title h3 { font-size: 13pt; font-weight: bold; color: #1a0960; text-transform: uppercase; letter-spacing: 1px; }
  .doc-title p { font-size: 10pt; color: #4219bf; margin-top: 3px; }
  .datos { border: 1px solid #ccc; border-radius: 3px; margin-bottom: 18px; overflow: hidden; }
  .datos table { width: 100%; border-collapse: collapse; }
  .datos td { padding: 5px 12px; font-size: 9.5pt; border-bottom: 1px solid #f0f0f0; }
  .datos td.label { background: #f5f5f5; color: #555; font-weight: bold; width: 28%; border-right: 1px solid #eee; }
  table.materias { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  table.materias thead tr th { background: #1a0960; color: white; padding: 6px 10px; font-size: 9pt; font-weight: normal; }
  table.materias thead tr th.center { text-align: center; }
  table.materias tbody tr td { padding: 5px 10px; border-bottom: 1px solid #eee; font-size: 9.5pt; }
  table.materias tbody tr:nth-child(even) td { background: #fafafa; }
  .cal { text-align: center; font-weight: bold; font-size: 11pt; }
  .aprobada { color: #15803d; }
  .reprobada { color: #dc2626; }
  .cursando  { color: #1d4ed8; }
  .summary-box { border: 1px solid #2d1590; border-radius: 4px; padding: 12px 16px; background: #f0f0ff; }
  .summary-box table { width: 100%; }
  .summary-box td { padding: 3px 8px; font-size: 9.5pt; }
  .summary-box .val { font-weight: bold; color: #1a0960; font-size: 12pt; text-align: center; }
  .footer-sign { margin-top: 50px; }
  .firma-block { display: inline-block; text-align: center; width: 45%; }
  .firma-block .line { border-top: 1px solid #333; padding-top: 6px; margin-top: 36px; font-size: 8.5pt; }
  .page-footer { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 8px; font-size: 7.5pt; color: #aaa; text-align: center; }
</style>
</head>
<body>

<div class="header">
  <h1>Tecnológico Nacional de México</h1>
  <h2>{{ config('app.name') }} · Servicios Escolares</h2>
</div>

<div class="doc-title">
  <h3>Boleta de Calificaciones</h3>
  <p>{{ $periodo->nombre }}</p>
</div>

<div class="datos">
  <table>
    <tr>
      <td class="label">Nombre</td><td colspan="3">{{ $alumno->nombre_completo }}</td>
    </tr>
    <tr>
      <td class="label">Matrícula</td><td>{{ $alumno->matricula }}</td>
      <td class="label">CURP</td><td>{{ $alumno->aspirante->curp ?? '—' }}</td>
    </tr>
    <tr>
      <td class="label">Carrera</td><td>{{ $alumno->carrera->nombre ?? '—' }}</td>
      <td class="label">Semestre</td><td>{{ $alumno->semestre_actual }}°</td>
    </tr>
    <tr>
      <td class="label">Período</td>
      <td>{{ $periodo->nombre }}</td>
      <td class="label">Fechas</td>
      <td>{{ $periodo->fecha_inicio->format('d/m/Y') }} – {{ $periodo->fecha_fin->format('d/m/Y') }}</td>
    </tr>
  </table>
</div>

<table class="materias">
  <thead>
    <tr>
      <th style="width:13%">Clave</th>
      <th>Materia</th>
      <th class="center" style="width:8%">Créd.</th>
      <th class="center" style="width:12%">Calificación</th>
      <th style="width:14%">Estado</th>
    </tr>
  </thead>
  <tbody>
    @forelse($materias as $h)
    <tr>
      <td>{{ $h->clave_materia ?? '—' }}</td>
      <td>{{ $h->materia }}</td>
      <td class="cal">{{ $h->creditos }}</td>
      <td class="cal {{ $h->status === 'acreditada' ? 'aprobada' : ($h->status === 'reprobada' ? 'reprobada' : 'cursando') }}">
        {{ $h->calificacion !== null ? number_format($h->calificacion, 1) : '—' }}
      </td>
      <td>{{ $h->status_nombre }}</td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center; padding:16px; color:#888;">Sin materias registradas para este período.</td></tr>
    @endforelse
  </tbody>
</table>

<div class="summary-box">
  <table>
    <tr>
      <td style="width:25%">Materias cursadas:</td>
      <td class="val">{{ $materias->count() }}</td>
      <td style="width:25%">Materias acreditadas:</td>
      <td class="val">{{ $materias->where('status','acreditada')->count() }}</td>
      <td style="width:25%">Promedio del período:</td>
      <td class="val {{ $promedioPeriodo >= 7 ? 'aprobada' : 'reprobada' }}">
        {{ $promedioPeriodo ? number_format($promedioPeriodo, 2) : '—' }}
      </td>
    </tr>
  </table>
</div>

<div class="footer-sign">
  <table width="100%"><tr>
    <td width="50%">
      <div class="firma-block">
        <div class="line">
          <strong>Jefe de Servicios Escolares</strong><br>
          Tecnológico Nacional de México
        </div>
      </div>
    </td>
    <td width="50%" style="text-align:right; vertical-align:bottom;">
      <div style="font-size:8pt; color:#bbb; text-align:right;">
        Emitido: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        Folio: BOL-{{ $alumno->matricula }}-{{ $periodo->anio }}{{ $periodo->ciclo }}
      </div>
    </td>
  </tr></table>
</div>

<div class="page-footer">
  Este documento es generado electrónicamente por {{ config('app.name') }}. Cualquier alteración lo invalida.
</div>

</body>
</html>
