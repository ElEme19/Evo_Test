<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Emisión - {{ $pedidos->first()->sucursal->nombre ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #2e7d32;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
            color: #555;
        }
        .header .subtitle {
            font-size: 12px;
            color: #2e7d32;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .info-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .info-item {
            margin: 0 10px 5px 0;
            min-width: 150px;
        }
        .info-item strong {
            display: block;
            font-weight: bold;
            color: #2e7d32;
        }
        .info-item span {
            display: inline-block;
            margin-top: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
        }
        th {
            background-color: #e8f5e9;
            border: 1px solid #ddd;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            color: #1b5e20;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px 5px;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        
        .footer-section {
            margin-top: 25px;
            font-size: 11px;
            padding: 10px;
            background-color: #f1f8e9;
            border-radius: 4px;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        .signature-box {
            width: 200px;
            border-top: 1px solid #2e7d32;
            padding-top: 5px;
            margin-top: 50px;
            text-align: center;
            color: #2e7d32;
            font-weight: bold;
        }
        .notes {
            margin-top: 20px;
            font-style: italic;
            padding: 10px;
            background-color: #f1f8e9;
            border-left: 3px solid #2e7d32;
        }
        .warning {
            margin-top: 15px;
            font-size: 11px;
            color: #d32f2f;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #ffcdd2;
            background-color: #ffebee;
        }
        .highlight {
            background-color: #c8e6c9;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>EVOBIKE S.A DE C.V</h1>
        <h2>Formulario de Emisión de Fábrica</h2>
        <div class="subtitle">SISTEMA DE GESTIÓN DE PEDIDOS</div>
    </div>

    <div class="info-top">
        <div class="info-item">
            <strong>Fecha:</strong> 
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <strong>Código:</strong> 
            <span>{{ $pedidos->first()->id_pedido ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <strong>Cliente:</strong> 
            <span>{{ $pedidos->first()->sucursal->nombre ?? 'Sucursal N/D' }}</span>
        </div>
        <div class="info-item">
            <strong>Distancia:</strong> 
            <span>{{ $pedidos->first()->distancia ?? 'N/A' }} KM</span>
        </div>
        <div class="info-item">
            <strong>Transporte:</strong> 
            <span>{{ $pedidos->first()->transporte ?? 'Evobike' }}</span>
        </div>
        <div class="info-item">
            <strong>Costo Envío:</strong> 
            <span>{{ $pedidos->first()->costo_envio ?? 'N/A' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Modelo</th>
                <th style="width: 15%;">Color</th>
                <th style="width: 8%;">Cantidad</th>
                <th style="width: 25%;">Número de serie</th>
                <th style="width: 32%;">Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $index => $pedido)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $pedido->bicicleta->modelo->nombre_modelo ?? 'N/D' }}</td>
                <td>{{ $pedido->bicicleta->color->nombre_color ?? 'N/D' }}</td>
                <td class="text-center">1</td>
                <td>{{ $pedido->num_chasis }}</td>
                <td>
                    @if($pedido->bicicleta->voltaje)
                        {{ $pedido->bicicleta->voltaje }}
                    @endif
                    {{ $pedido->bicicleta->observaciones ?? '' }}
                </td>
            </tr>
            @endforeach
            <tr class="highlight">
                <td colspan="3" class="text-right"><strong>TOTAL DE BICICLETAS:</strong></td>
                <td class="text-center"><strong>{{ $pedidos->count() }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer-section">
        <p>Este pedido es por duplicado, uno se enviará al destino con la mercancía, otro se guardará en fábrica y el archivo electrónico se enviará al departamento comercial.</p>
    </div>

    <div class="signature-container">
        <div class="signature-box">
            Sello o firma del responsable de fábrica:
        </div>
        <div class="signature-box">
            Firma del inspector de calidad:
        </div>
        <div class="signature-box">
            Firma del chofer:<br>
            Teléfono chofer: {{ $pedidos->first()->telefono_chofer ?? 'N/A' }}
        </div>
    </div>

    <div class="footer-section">
        <p><strong>Recibo de Emisión</strong></p>
        <p>Verificación de orden de emisión: ☐ Verificado ☐ Error de verificación</p>
    </div>

    <div class="signature-box" style="width: 100%;">
        Firma del responsable de la tienda (el recibo se recibirá tras confirmar el pedido):
    </div>

    <div class="notes">
        <p><strong>Observación:</strong></p>
        <p>Para cualquier aclaración o informe de daños comuníquese al siguiente número: {{ $pedidos->first()->telefono_contacto ?? '56 4899 6759' }}</p>
    </div>

    <div class="warning">
        <p>El pedido deberá ser supervisado por el cliente, una vez firmado este documento la empresa no se hace responsable.</p>
    </div>

</body>
</html>