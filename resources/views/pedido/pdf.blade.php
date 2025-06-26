<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <title>Formulario de Emisión de Fábrica - {{ $pedido->id_pedido }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 4px; }
        .header-table th, .header-table td { border: 1px solid #000; padding: 6px; }
        .logo { height: 30px; vertical-align: middle; }
        .header-logo { text-align: center; font-weight: bold; font-size: 16px; }
        .title { font-weight: bold; font-size: 20px; font-style: italic; text-align: center; padding: 8px 0; }
        .emision { font-weight: bold; font-style: italic; text-align: center; }
        .efirma { font-style: italic; text-align: center; height: 60px; vertical-align: top; }
        .notas, table.notas td { border: none !important; }
        .small { font-size: 11px; }
        .center { text-align: center; }
        .no-border { border: none !important; }
        .footer-note { font-size: 8px; }
        .footer-red { color: red; font-size: 8px; }
        .merged-cell { border-top: none !important; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <table class="table header-table">
        <tr>
            <td colspan="6" class="header-logo" style="text-align: center;">
                <img src="{{ public_path('images/logo.jpg') }}" style="height: 50px;" alt="Logo">
            </td>
        </tr>
        <tr>
            <td colspan="6" class="title">Formulario de Emisión de Fábrica</td>
        </tr>
         <tr>
                <td style="width:10%; text-align: center;">
                    <strong>Fecha:</strong><br>{{ now()->format('d/m/Y') }}
                </td>
                <td style="width:18%; text-align: center;">
                    <strong>Código:</strong><br>{{ $pedido->id_pedido }}
                </td>
                <td style="width:21%; text-align: center;">
                    <strong>Cliente:</strong><br>{{ $pedido->sucursal->nombre_sucursal }}
                </td>
                <td style="width:10%; text-align: center;">
                    <strong>Distancia:</strong><br>{{ $pedido->sucursal->distancia_km ?? 'N/D' }} KM
                </td>
                <td style="width:25%; text-align: center;">
                    <strong>Transporte:</strong><br>Evobike
                </td>
                <td style="width:16%; text-align: center;">
                    <strong>Costo Envío:</strong><br>{{ $pedido->costo_envio ?? '0.00 MXN' }}
                </td>
        </tr>

    </table>

   <!-- Detalle de bicis -->
<table class="table header-table">
    <thead>
        <tr>
            <th class="small center" style="width:10%;">Numero</th>
            <th class="small" style="width:18%;">Modelo</th>
            <th class="small" style="width:21%;">Color</th>
            <th class="small center" style="width:10%;">Cantidad</th>
            <th class="small" style="width:25%;">No. Frame</th>
            <th class="small" style="width:16%;">Observación</th>
        </tr>
    </thead>
    <tbody>
        @php
            // 1) Armo la estructura modelo → voltaje → color → [bicis]
            $modelGroups = [];
            foreach($pedido->bicicletas as $bic) {
                $m = $bic->modelo->nombre_modelo ?? 'N/D';
                $v = $bic->voltaje               ?? '';
                $c = $bic->color->nombre_color   ?? 'N/D';
                $modelGroups[$m]['voltajes'][$v]['colores'][$c][] = $bic;
            }
            $rowNumber = 1;
        @endphp

        @foreach($modelGroups as $modelName => $modelGroup)
            @php
                // Calcular cuántas filas ocupa todo el modelo
                $modelRowspan = 0;
                foreach($modelGroup['voltajes'] as $vg) {
                    foreach($vg['colores'] as $bicis) {
                        $modelRowspan += count($bicis);
                    }
                }
                $printedModel = false;
            @endphp

            @foreach($modelGroup['voltajes'] as $voltajeName => $voltGroup)
                @php
                    // Filas que ocupa este voltaje
                    $voltajeRowspan = 0;
                    foreach($voltGroup['colores'] as $bicis) {
                        $voltajeRowspan += count($bicis);
                    }
                    $printedVoltaje = false;
                @endphp

                @foreach($voltGroup['colores'] as $colorName => $bicis)
                    @php
                        $colorRowspan = count($bicis);
                        $printedColor  = false;
                    @endphp

                    @foreach($bicis as $bic)
                        <tr>
                            <td class="center">{{ $rowNumber }}</td>

                            {{-- Modelo (sólo la primera vez para este modelo) --}}
                            @if(! $printedModel)
                                <td style="text-align: center;" rowspan="{{ $modelRowspan }}">
                                    {{ $modelName }}
                                </td>
                                @php $printedModel = true; @endphp
                            @endif

                            {{-- Color y Cantidad (primera vez en este color) --}}
                            @if(! $printedColor)
                                <td style="text-align: center;" rowspan="{{ $colorRowspan }}">
                                    {{ $colorName }}
                                </td>
                                <td class="center" rowspan="{{ $colorRowspan }}">
                                    {{ $colorRowspan }}
                                </td>
                                @php $printedColor = true; @endphp
                            @endif

                            {{-- No. Frame (cada fila) --}}
                            <td style="text-align: center;">
                                {{ $bic->num_chasis }}
                            </td>

                            {{-- Observación / Voltaje (primera vez en este voltaje) --}}
                            @if(! $printedVoltaje)
                                <td style="text-align: center;" rowspan="{{ $voltajeRowspan }}">
                                    {{ $voltajeName }}
                                </td>
                                @php $printedVoltaje = true; @endphp
                            @endif
                        </tr>
                        @php $rowNumber++; @endphp
                    @endforeach

                @endforeach
            @endforeach

        @endforeach
    </tbody>
</table>


    <!-- Resto del documento (firmas y notas) -->
    <table class="table header-table">
        <tr>
            <td style="width: 59%; height: 90px; font-size: 11px; padding: 4px; font-style: italic; text-align: center; font-weight: bold;">
                Este pedido es por duplicado, uno se enviará al destino con la mercancía, otro se guardará en fábrica y el archivo electrónico se enviará al departamento comercial.
            </td>
            <td rowspan="2" style="width: 41%; vertical-align: top; font-size: 11px; padding: 4px; font-style: italic; text-align: center; font-weight: bold;">
                Sello o firma del responsable de fábrica:
            </td>
        </tr>
        <tr>
            <td style="padding: 2px; font-size: 10px; height: 20px; line-height: 1;">
                Firma del inspector de calidad:
            </td>
        </tr>
    </table>


<table class="table header-table">
    <tr>
        <td style="width: 40%;  padding: 5px;">
            Firma del chofer:<br>
        </td>
        <td style="width: 60%;  padding: 5px;">
            Teléfono chofer:<br>
        </td>
    </tr>
       
</table>

<table class="table header-table">
    <tr>
        <td class="emision">
            Recibo de Emicion<br>
        </td>
    </tr> 
</table>

    <table style="width: 100%; border-collapse: collapse; border-left: 1px solid black; border-right: 1px solid black;">
    <tr style="border-bottom: 1px solid black;">
        <td style="width: 33%; padding: 5px;">Verificación de orden de emisión</td>
        <td style="width: 33%; padding: 5px;">Verificado</td>
        <td style="width: 33%; padding: 5px;">Error de verificarlo</td>
    </tr>
</table>

<table class="table header-table">
    <tr>
            <td class="efirma">Firma del responsable de la tienda (el recibo se recibirá tras confirmar el pedido):</td>
        </tr>
</table>



<table style="width: 100%; border-collapse: collapse; border: none;">
    <tr style="border: 1px solid #000;">
        <td style="border: none; padding: 5px;">Observación:</td>
    </tr>
    <tr style="border: 1px solid #000;">
        <td style="border: none; padding: 5px;">
            Para cualquier aclaración o informe de daños comuníquese al siguiente número {{ $pedido->sucursal->telefono ?? '56 4899 6759' }}
        </td>
    </tr>
    <tr style="border: 1px solid #000;">
       <td style="border: none; padding: 5px; height: 25px; color: red;">
    El pedido deberá ser supervisado por el cliente, una vez firmado este documento la empresa no se hace responsable
</td>

    </tr>
</table>


<footer style="
    position: fixed;
    bottom: 0;
    width: 100%;
    padding: 8px 0;
    text-align: center;
    font-size: 10px;
    color: #555;
    background-color: #f8f8f8;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
">
    <!-- Contenedor flexible para texto e icono -->
    <div style="display: flex; align-items: center; height: 22px; position: relative;">
        <svg xmlns="http://www.w3.org/2000/svg" 
             width="14" 
             height="14" 
             fill="#4a6baf" 
             viewBox="0 0 16 16"
             style="vertical-align: middle; margin-right: 5px;">
            <path d="M8 1a5.53 5.53 0 0 0-3.594 1.343A5.49 5.49 0 0 0 8 0a5.49 5.49 0 0 0 3.594 2.343A5.53 5.53 0 0 0 8 1z"/>
            <path d="M4.406 3.3C2.664 4.045 1.5 5.897 1.5 8c0 2.485 2.015 4.5 4.5 4.5H11a4.5 4.5 0 0 0 0-9 5.53 5.53 0 0 0-3.594 1.3z"/>
        </svg>
        <span style="line-height: 22px;">Powered By: CloudLabs</span>
        
        <!-- Imagen con ajuste vertical -->
        <img src="{{ public_path('images/CloudLabs.png') }}" 
             style="height: 15px; width: auto; display: block; position: relative; top: 3px; margin-left: 1px;">
    </div>
</footer>


</body>
</html>
