<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; font-size: 11pt; color: #1a1a1a; padding: 40px 50px; }
  .header { text-align: center; border-bottom: 2px solid #2d1590; padding-bottom: 16px; margin-bottom: 24px; }
  .header h1 { font-size: 13pt; font-weight: bold; color: #1a0960; letter-spacing: 1px; text-transform: uppercase; }
  .header h2 { font-size: 11pt; color: #4219bf; margin-top: 4px; }
  .header p  { font-size: 9pt; color: #666; margin-top: 2px; }
  .doc-title { text-align: center; margin: 24px 0; }
  .doc-title h3 { font-size: 14pt; font-weight: bold; color: #1a0960; letter-spacing: 2px; text-transform: uppercase; border: 2px solid #2d1590; display: inline-block; padding: 6px 24px; }
  .body-text { font-size: 11pt; line-height: 1.8; text-align: justify; margin: 20px 0; }
  .body-text strong { color: #1a0960; }
  .datos-grid { margin: 20px 0; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
  .datos-grid table { width: 100%; border-collapse: collapse; }
  .datos-grid td { padding: 7px 12px; font-size: 10pt; border-bottom: 1px solid #eee; }
  .datos-grid td:first-child { color: #666; font-weight: bold; width: 35%; background: #f8f8f8; }
  .footer { margin-top: 60px; }
  .firma { display: inline-block; text-align: center; width: 45%; }
  .firma .linea { border-top: 1px solid #333; padding-top: 6px; margin-top: 40px; font-size: 9pt; }
  .sello { text-align: right; font-size: 8pt; color: #999; margin-top: 30px; }
  .folio { text-align: right; font-size: 8.5pt; color: #888; margin-bottom: 10px; }
  .aviso { background: #fff8e1; border: 1px solid #f9a825; border-radius: 4px; padding: 10px 14px; font-size: 9pt; color: #5d4037; margin-top: 20px; }
</style>
</head>
<body>

<div class="header">
  <h1>Tecnológico Nacional de México</h1>
  <h2>{{ config('app.edusige.campus') }}</h2>
  <p>Departamento de Servicios Escolares</p>
</div>

<div class="folio">Folio: CE-{{ strtoupper($alumno->matricula) }}-{{ date('Ymd') }}</div>

<div class="doc-title">
  <h3>Constancia de Estudios</h3>
</div>

<p class="body-text">
  El que suscribe, Jefe del Departamento de Servicios Escolares del {{ config('app.edusige.campus') }},
  hace constar que <strong>{{ strtoupper($alumno->nombre_completo) }}</strong>, con número de matrícula
  <strong>{{ $alumno->matricula }}</strong>, se encuentra cursando estudios de nivel <strong>Licenciatura</strong>
  en la carrera de <strong>{{ $alumno->carrera->nombre ?? 'N/D' }}</strong> (clave {{ $alumno->carrera->clave ?? '' }}),
  correspondiente al <strong>{{ $alumno->semestre_actual }}° Semestre</strong>.
</p>

@if($periodoActual)
<p class="body-text">
  Actualmente inscrito(a) en el período escolar
  <strong>{{ $periodoActual->nombre }}</strong>
  ({{ $periodoActual->fecha_inicio->format('d/m/Y') }} al {{ $periodoActual->fecha_fin->format('d/m/Y') }}).
</p>
@endif

<div class="datos-grid">
  <table>
    <tr><td>Nombre completo</td><td>{{ $alumno->nombre_completo }}</td></tr>
    <tr><td>Matrícula</td><td>{{ $alumno->matricula }}</td></tr>
    <tr><td>CURP</td><td>{{ $alumno->aspirante->curp ?? '—' }}</td></tr>
    <tr><td>Carrera</td><td>{{ $alumno->carrera->nombre ?? '—' }}</td></tr>
    <tr><td>Semestre actual</td><td>{{ $alumno->semestre_actual }}°</td></tr>
    <tr><td>Promedio general</td><td>{{ $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : 'En proceso' }}</td></tr>
    <tr><td>Status</td><td>Alumno regular activo</td></tr>
    <tr><td>Fecha de ingreso</td><td>{{ $alumno->periodoIngreso?->nombre ?? '—' }}</td></tr>
  </table>
</div>

<p class="body-text">
  La presente constancia se expide a petición del interesado(a) el día
  <strong>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</strong>,
  para los fines que al portador convengan.
</p>

@if($adeudosPendientes)
<div class="aviso">
  <strong>Nota:</strong> El alumno cuenta con adeudos pendientes registrados en el sistema. Esta constancia es válida únicamente para trámites que no requieran estar al corriente en pagos.
</div>
@endif

<div class="footer">
  <table width="100%"><tr>
    <td width="50%">
      <div class="firma">
        <div class="linea">
          <strong>Jefe del Depto. de Servicios Escolares</strong><br>
          {{ config('app.edusige.campus') }}
        </div>
      </div>
    </td>
    <td width="50%" style="text-align:right; vertical-align:bottom;">
      <div style="font-size:8pt; color:#aaa;">
        Documento generado electrónicamente<br>
        {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}<br>
        {{ config('app.edusige.campus') }} · Depto. de Servicios Escolares
      </div>
    </td>
  </tr></table>
</div>

</body>
</html>
