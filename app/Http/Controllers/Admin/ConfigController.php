<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends Controller
{
    public function index()
    {
        $config = [
            'institucion'         => config('app.edusige.institucion'),
            'monto_ficha'         => config('app.edusige.monto_ficha'),
            'dias_vigencia_ficha' => config('app.edusige.dias_vigencia_ficha'),
        ];

        return view('admin.config.index', compact('config'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'institucion'         => ['required', 'string', 'max:200'],
            'monto_ficha'         => ['required', 'numeric', 'min:1'],
            'dias_vigencia_ficha' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        // Actualizar el .env
        $this->actualizarEnv([
            'EDUSIGE_INSTITUCION'         => '"' . $request->institucion . '"',
            'EDUSIGE_MONTO_FICHA'         => $request->monto_ficha,
            'EDUSIGE_DIAS_VIGENCIA_FICHA' => $request->dias_vigencia_ficha,
        ]);

        Artisan::call('config:clear');

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    private function actualizarEnv(array $valores): void
    {
        $rutaEnv = base_path('.env');
        $contenido = file_get_contents($rutaEnv);

        foreach ($valores as $clave => $valor) {
            if (str_contains($contenido, $clave . '=')) {
                $contenido = preg_replace(
                    "/^{$clave}=.*/m",
                    "{$clave}={$valor}",
                    $contenido
                );
            } else {
                $contenido .= "\n{$clave}={$valor}";
            }
        }

        file_put_contents($rutaEnv, $contenido);
    }
}
