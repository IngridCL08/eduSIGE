<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; font-size: 9pt; color: #1a1a1a; padding: 30px 40px; }
  .header { text-align: center; border-bottom: 2px solid #2d1590; padding-bottom: 12px; margin-bottom: 16px; }
  .header h1 { font-size: 12pt; font-weight: bold; color: #1a0960; text-transform: uppercase; }
  .header h2 { font-size: 10pt; color: #4219bf; margin-top: 3px; }
  .doc-title { text-align: center; margin: 14px 0; }
  .doc-title h3 { font-size: 12pt; font-weight: bold; color: #1a0960; letter-spacing: 1px; text-transform: uppercase; }
  .alumno-info { display: table; width: 100%; border: 1px solid #ccc; margin-bottom: 16px; border-radius: 3px; }
  .alumno-info table { width: 100%; border-collapse: collapse; }
  .alumno-info td { padding: 5px 10px; font-size: 9pt; }
  .alumno-info td.label { color: #555; font-weight: bold; background: #f5f5f5; width: 22%; border-right: 1px solid #eee; }
  .periodo-section { margin-bottom: 14px; }
  .periodo-label { background: #1a0960; color: white; font-weight: bold; padding: 4px 10px; font-size: 9pt; border-radius: 2px; margin-bottom: 4px; }
  table.materias { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
  table.materias th { background: #2d1590; color: white; padding: 4px 8px; text-align: left; font-weight: normal; }
  table.materias th.num { text-align: center; }
  table.materias td { padding: 3px 8px; border-bottom: 1px solid #eee; }
  table.materias td.cal { text-align: center; font-weight: bold; }
  table.materias td.acreditada { color: #15803d; }
  table.materias td.reprobada  { color: #dc2626; }
  table.materias td.cursando   { color: #1d4ed8; }
  table.materias tr:nth-child(even) td { background: #fafafa; }
  .resumen { margin-top: 14px; border: 1px solid #ccc; border-radius: 3px; padding: 10px 14px; font-size: 9pt; }
  .resumen table { width: 100%; }
  .resumen td { padding: 3px 8px; }
  .resumen td.label { color: #555; font-weight: bold; width: 45%; }
  .resumen td.valor { font-weight: bold; color: #1a0960; font-size: 10pt; }
  .footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 7.5pt; color: #888; text-align: center; }
</style>
</head>
<body>

<div class="header">
  <h1>Tecnológico Nacional de México</h1>
  <h2>{{ config('app.edusige.campus') }} · Departamento de Servicios Escolares</h2>
</div>

<div class="doc-title"><h3>Kárdex Académico</h3></div>

<div class="alumno-info">
  <table>
    <tr>
      <td class="label">Matrícula</td><td>{{ $alumno->matricula }}</td>
      <td class="label">Nombre</td><td colspan="3">{{ $alumno->nombre_completo }}</td>
    </tr>
    <tr>
      <td class="label">Carrera</td><td colspan="2">{{ $alumno->carrera->nombre ?? '—' }}</td>
      <td class="label">Semestre actual</td><td>{{ $alumno->semestre_actual }}°</td>
    </tr>
    <tr>
      <td class="label">CURP</td><td>{{ $alumno->aspirante->curp ?? '—' }}</td>
      <td class="label">Fecha ingreso</td><td>{{ $alumno->periodoIngreso?->nombre ?? '—' }}</td>
    </tr>
    <tr>
      <td class="label">Promedio gral.</td>
      <td><strong>{{ $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : 'En proceso' }}</strong></td>
      <td class="label">Créditos acum.</td>
      <td>{{ $creditosAprobados }}</td>
    </tr>
  </table>
</div>

@forelse($historial as $periodoId => $materiasPeriodo)
  @php $per = $periodos[$periodoId] ?? null; @endphp
  <div class="periodo-section">
    <div class="periodo-label">
      {{ $per ? $per->nombre : "Período #{$periodoId}" }}
    </div>
    <table class="materias">
      <thead>
        <tr>
          <th style="width:12%">Clave</th>
          <th>Materia</th>
          <th class="num" style="width:8%">Créd.</th>
          <th class="num" style="width:10%">Calif.</th>
          <th style="width:13%">Estado</th>
        </tr>
      </thead>
      <tbody>
        @foreach($materiasPeriodo as $h)
        <tr>
          <td>{{ $h->clave_materia ?? '—' }}</td>
          <td>{{ $h->materia }}</td>
          <td class="cal">{{ $h->creditos }}</td>
          <td class="cal {{ $h->status }}">
            {{ $h->calificacion !== null ? number_format($h->calificacion, 1) : '—' }}
          </td>
          <td>{{ $h->status_nombre }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@empty
  <p style="text-align:center; color:#888; padding:20px;">Sin historial académico registrado.</p>
@endforelse

<div class="resumen">
  <table>
    <tr>
      <td class="label">Total créditos aprobados:</td>
      <td class="valor">{{ $creditosAprobados }}</td>
      <td class="label">Promedio general:</td>
      <td class="valor">{{ $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—' }}</td>
    </tr>
  </table>
</div>

<div class="footer">
  Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} —
  {{ config('app.edusige.campus') }} · Depto. de Servicios Escolares · Este documento es de uso oficial.
</div>

</body>
</html>
