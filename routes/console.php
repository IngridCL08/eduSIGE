<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar generación de reporte mensual
Schedule::command('edusige:reporte-mensual')->monthlyOn(1, '06:00');

// Marcar fichas vencidas automáticamente (diario a medianoche)
Schedule::command('edusige:marcar-vencidas')->daily();
