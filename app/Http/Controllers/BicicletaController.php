```php
<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarTrabajoImpresion;
use Illuminate\Http\Request;
use App\Models\Bicicleta;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Lote;
use App\Models\TipoStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BicicletaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'num_chasis' => 'required|string|exists:bicicleta,num_chasis',
            'id_color' => 'required|string|exists:color_modelo,id_colorM',
            'id_lote' => 'required|string|exists:lote,id_lote',
            'id_tipoStock' => 'required|string|exists:tipo_stock,id_tipoStock',
            'voltaje' => 'nullable|string|max:10',
            'error_iden_produccion' => 'nullable|string|max:255',
        ]);

    DB::beginTransaction();

        try {
            // 1. Actualización en base de datos
            $updateData = [
                'id_color' => $validated['id_color'],
                'id_lote' => $validated['id_lote'],
                'id_tipoStock' => $validated['id_tipoStock'],
                'codigo_barras' => $validated['num_chasis'],
                'updated_at' => now()
            ];

            // Campos opcionales
            if (!empty($validated['voltaje'])) {
                $updateData['voltaje'] = $validated['voltaje'];
            }
            if (!empty($validated['error_iden_produccion'])) {
                $updateData['error_iden_produccion'] = $validated['error_iden_produccion'];
            }

            $updateResult = DB::table('bicicleta')
                ->where('num_chasis', $validated['num_chasis'])
                ->update($updateData);

            if ($updateResult === 0) {
                throw new \Exception("No se actualizó ningún registro. Verifica el número de chasis.");
            }

            // 2. Enviar trabajo de impresión
            $this->dispatchPrintJob($validated['num_chasis'], [
                'modelo' => 'Bicicleta',
                'color_id' => $validated['id_color'],
                'lote_id' => $validated['id_lote']
            ]);

        DB::commit();

            return back()
                ->with('success', '¡Bicicleta actualizada correctamente! El código de barras se está imprimiendo.')
                ->with('print_status', 'en_cola');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en store:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Dispatch print job with additional metadata
     */
    protected function dispatchPrintJob(string $codigo, array $metadata = []): void
    {
        EnviarTrabajoImpresion::dispatch($codigo, $metadata)
            ->onQueue('impresiones')
            ->delay(now()->addSeconds(5)); // Pequeño retardo para dar tiempo a la transacción
    }
}