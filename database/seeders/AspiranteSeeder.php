<?php

namespace Database\Seeders;

use App\Models\Aspirante;
use App\Models\FichaPago;
use App\Models\Carrera;
use App\Models\Periodo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AspiranteSeeder extends Seeder
{
    private array $municipios = [
        'Monterrey', 'Guadalupe', 'San Nicolás de los Garza', 'Apodaca',
        'Escobedo', 'Santa Catarina', 'San Pedro Garza García',
    ];

    private array $bachilleratos = [
        'Preparatoria #1 UANL',
        'Preparatoria #15 UANL',
        'CETIS 80',
        'CONALEP Monterrey',
        'Colegio Americano de Monterrey',
        'Prepa Tec Campus Monterrey',
        'Instituto Regiomontano',
    ];

    public function run(): void
    {
        $carreras = Carrera::where('activa', true)->get();
        $periodo  = Periodo::where('activo', true)->first();

        if (! $periodo || $carreras->isEmpty()) {
            $this->command->warn('⚠️  No hay período activo o carreras disponibles.');
            return;
        }

        $statuses = [
            'registrado'            => 5,
            'ficha_generada'        => 10,
            'ficha_pagada'          => 20,
            'documentos_entregados' => 8,
            'admitido'              => 5,
            'no_admitido'           => 2,
        ];

        $contador = 1;

        foreach ($statuses as $status => $cantidad) {
            for ($i = 0; $i < $cantidad; $i++) {
                $sexo    = fake()->randomElement(['M', 'F']);
                $nombre  = $sexo === 'M' ? fake()->firstNameMale() : fake()->firstNameFemale();
                $apellidoP = fake()->lastName();
                $apellidoM = fake()->lastName();
                $carrera   = $carreras->random();

                $folio = 'ASP-' . $periodo->anio . '-' . str_pad($contador, 6, '0', STR_PAD_LEFT);

                $aspirante = Aspirante::create([
                    'folio'                  => $folio,
                    'nombre'                 => $nombre,
                    'apellido_paterno'       => $apellidoP,
                    'apellido_materno'       => $apellidoM,
                    'curp'                   => $this->generarCurp($nombre, $apellidoP, $apellidoM, $sexo),
                    'fecha_nacimiento'       => fake()->dateTimeBetween('-25 years', '-17 years')->format('Y-m-d'),
                    'sexo'                   => $sexo,
                    'email'                  => strtolower(Str::ascii($nombre) . '.' . $contador . '@ejemplo.com'),
                    'telefono'               => '81' . fake()->numerify('########'),
                    'direccion'              => fake()->streetAddress(),
                    'colonia'                => 'Col. ' . fake()->word(),
                    'municipio'              => fake()->randomElement($this->municipios),
                    'estado'                 => 'Nuevo León',
                    'cp'                     => '6' . fake()->numerify('####'),
                    'bachillerato'           => fake()->randomElement($this->bachilleratos),
                    'promedio_bachillerato'  => fake()->randomFloat(2, 7.0, 10.0),
                    'anio_egreso'            => fake()->numberBetween(2021, 2024),
                    'carrera_id'             => $carrera->id,
                    'periodo_id'             => $periodo->id,
                    'status'                 => $status,
                ]);

                // Crear ficha si el status lo requiere
                if (in_array($status, ['ficha_generada', 'ficha_pagada', 'documentos_entregados', 'admitido', 'no_admitido'])) {
                    $this->crearFicha($aspirante, $status, $periodo->anio, $contador);
                }

                $contador++;
            }
        }

        $this->command->info("✅ {$contador} aspirantes creados con fichas de prueba.");
    }

    private function crearFicha(Aspirante $aspirante, string $status, int $anio, int $num): void
    {
        $folioFicha = 'FP-' . $anio . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);
        $emision    = Carbon::now()->subDays(rand(1, 30));
        $vencimiento = $emision->copy()->addDays(5);

        $esPagada = in_array($status, ['ficha_pagada', 'documentos_entregados', 'admitido', 'no_admitido']);

        FichaPago::create([
            'aspirante_id'     => $aspirante->id,
            'folio_ficha'      => $folioFicha,
            'monto'            => config('app.edusige.monto_ficha', 500.00),
            'concepto'         => 'Ficha de inscripción ' . $anio,
            'fecha_emision'    => $emision->format('Y-m-d'),
            'fecha_vencimiento' => $vencimiento->format('Y-m-d'),
            'status'           => $esPagada ? 'pagado' : 'pendiente',
            'fecha_pago'       => $esPagada ? $emision->addDays(rand(1, 4)) : null,
            'metodo_pago'      => $esPagada ? fake()->randomElement(['conekta', 'paypal', 'transferencia', 'efectivo']) : null,
            'referencia_pago'  => $esPagada ? strtoupper(Str::random(12)) : null,
        ]);
    }

    private function generarCurp(string $nombre, string $ap, string $am, string $sexo): string
    {
        $chars  = strtoupper(Str::ascii($ap[0] . $this->primeraVocalInterna($ap) . $am[0] . $nombre[0]));
        $anio   = fake()->numberBetween(98, 07);
        $mes    = fake()->numberBetween(1, 12);
        $dia    = fake()->numberBetween(1, 28);
        $estado = fake()->randomElement(['NL', 'MX', 'JC', 'VZ', 'GJ']);
        $alfa   = strtoupper(Str::random(3));
        $digito = fake()->numberBetween(0, 9);

        return substr($chars . sprintf('%02d%02d%02d', $anio, $mes, $dia) . $sexo . $estado . $alfa . $digito, 0, 18);
    }

    private function primeraVocalInterna(string $str): string
    {
        $vocales = ['A', 'E', 'I', 'O', 'U'];
        $str = strtoupper(Str::ascii($str));
        for ($i = 1; $i < strlen($str); $i++) {
            if (in_array($str[$i], $vocales)) {
                return $str[$i];
            }
        }
        return 'X';
    }
}
