<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autorizacion;
use App\Models\Bicicleta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AutorizacionController extends Controller
{
    /**
     * Endpoint que procesa la autorización desde el correo
     * @param string $token
     * @param string $accion ('approve' o 'reject')
     */
    public function responder($token, $accion)
{
    try {
        $autorizacion = Autorizacion::where('token', $token)->first();

        if (!$autorizacion) {
            return response()->view('autorizacion.error', [
                'mensaje' => 'Token inválido o inexistente.'
            ]);
        }

        if ($autorizacion->estado !== 'pendiente') {
            return response()->view('autorizacion.error', [
                'mensaje' => 'Esta solicitud ya fue procesada previamente.'
            ]);
        }

        if (!in_array($accion, ['approve', 'reject'])) {
            return response()->view('autorizacion.error', [
                'mensaje' => 'Acción no válida.'
            ]);
        }

        // Guardar estado con los valores correctos de la base de datos
        $autorizacion->estado = $accion === 'approve' ? 'autorizado' : 'rechazado';
        $autorizacion->save();

        // Si fue autorizado, imprimir automáticamente
        if ($autorizacion->estado === 'autorizado') {
            $bici = Bicicleta::where('num_chasis', $autorizacion->num_chasis)->first();

            if ($bici) {
                $apiKey = env('PRINTNODE_API_KEY_2'); 
                $printerId = env('PRINTNODE_PRINTER_ID_2');

                $bicicletaController = new \App\Http\Controllers\BicicletaController();
                try {
                    $bicicletaController->enviarPrintNode(
                        $bici->num_chasis,
                        $bici->codigo_barras ?? 'Etiqueta',
                        $apiKey,
                        (int) $printerId
                    );
                } catch (\Exception $e) {
                    Log::error('Error al imprimir desde autorización: ' . $e->getMessage());
                }
            }
        }

        // Retornar vista con mensaje final
        return response()->view('autorizacion.success', [
            'mensaje' => $autorizacion->estado === 'autorizado'
                ? '✅ Solicitud autorizada y etiqueta enviada a impresión.'
                : '❌ Solicitud rechazada.'
        ]);

    } catch (\Exception $e) {
        Log::error('Error en responder autorización: ' . $e->getMessage());

        return response()->view('autorizacion.error', [
            'mensaje' => 'Ocurrió un error al procesar la solicitud.'
        ]);
    }
}

}
