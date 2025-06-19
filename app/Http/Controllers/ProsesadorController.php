<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProsesadorController extends Controller
{
    /**
     * Muestra el formulario para cargar el Excel (ahora en import.blade.php)
     */
    public function formulario()
    {
        return view('Mexico.import');
    }

    /**
     * Procesa el Excel subido y guarda en la tabla 'bicicleta'
     */
    public function procesarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx',
        ]);

        $archivo     = $request->file('archivo');
        $spreadsheet = IOFactory::load($archivo->getRealPath());
        $hoja        = $spreadsheet->getActiveSheet();

        $model_map = [
            'VMP S5'  => 'EVO_VMPS5',
            'SOL PRO' => 'EVO_SOL_PRO',
            // añadí más si hace falta
        ];

        $registros     = [];
        $modelo_actual = null;

        foreach ($hoja->getRowIterator() as $fila) {
            $celdas = $fila->getCellIterator();
            $celdas->setIterateOnlyExistingCells(false);
            $valores = [];

            foreach ($celdas as $celda) {
                $valores[] = trim((string) $celda->getValue());
            }

            if (preg_match('/^\s*MODEL:\s*(.+)$/i', $valores[0] ?? '', $matches)) {
                $nombre_modelo  = trim($matches[1]);
                $modelo_actual  = $model_map[$nombre_modelo] ?? null;
                continue;
            }

            if (! $modelo_actual) {
                continue;
            }

            foreach ([1, 5] as $i) {
                if (! isset($valores[$i])) {
                    continue;
                }
                $valor = trim($valores[$i]);
                if (str_starts_with($valor, 'H')) {
                    $registros[] = [
                        'num_chasis' => $valor,
                        'id_modelo'  => $modelo_actual,
                    ];
                }
            }
        }

        if (empty($registros)) {
            return back()->withErrors(['No se encontraron registros válidos en el Excel.']);
        }

        $insertados = 0;
        foreach ($registros as $registro) {
            try {
                DB::table('bicicleta')->insert($registro);
                $insertados++;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    Log::warning("Chasis duplicado (omitido): " . $registro['num_chasis']);
                } else {
                    throw $e;
                }
            }
        }

        return back()->with('status', "Proceso completado: se insertaron {$insertados} registros.");
    }
}
