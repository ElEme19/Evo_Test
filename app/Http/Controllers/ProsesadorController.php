<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProsesadorController extends Controller
{
    /**
     * Muestra el formulario para cargar el Excel
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
    set_time_limit(0);

    $request->validate([
        'archivo' => 'required|file|mimes:xls,xlsx',
    ]);

    $archivo     = $request->file('archivo');
    $spreadsheet = IOFactory::load($archivo->getRealPath());
    $hoja        = $spreadsheet->getActiveSheet();

    $registros   = [];
    $orden_excel = 1;

    foreach ($hoja->getRowIterator() as $fila) {
        $celdas = $fila->getCellIterator();
        $celdas->setIterateOnlyExistingCells(false);
        $valores = [];

        foreach ($celdas as $celda) {
            $valores[] = trim((string) $celda->getValue());
        }

        // Buscar chasis en columnas 1 y 5
        foreach ([1, 5] as $i) {
            if (! isset($valores[$i])) {
                continue;
            }

            $valor = trim($valores[$i]);

            if (str_starts_with($valor, 'H')) {
                $registros[] = [
                    'num_chasis'  => $valor,
                    'orden_excel' => $orden_excel++,
                ];
            }
        }
    }

    if (empty($registros)) {
        return back()->withErrors(['No se encontraron registros válidos en el Excel.']);
    }

    // Configuración de PrintNode
    $apiKey    = config('printnode.api_key');
    $printerId = config('printnode.printer_id');

    $resultados = [];

    foreach ($registros as $registro) {
        $codigo = $registro['num_chasis'];

        try {
            $resultado = $this->enviarPrintNode($codigo, null, $apiKey, $printerId);

            $resultados[] = [
                'num_chasis' => $codigo,
                'status'     => 'success',
                'message'    => $resultado['message'],
                'data'       => $resultado['data'] ?? null,
            ];
        } catch (\Exception $e) {
            $resultados[] = [
                'num_chasis' => $codigo,
                'status'     => 'error',
                'message'    => $e->getMessage(),
            ];
        }
    }

    return response()->json([
        'status'     => 'completed',
        'total'      => count($resultados),
        'resultados' => $resultados,
        'timestamp'  => now()->toDateTimeString(),
    ]);
}
}