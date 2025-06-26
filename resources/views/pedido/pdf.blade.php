<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $pedido->id_pedido ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 10px;
            padding: 0;
            color: #333;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 5px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #2e7d32;
            font-weight: bold;
        }
        .header h2 {
            margin: 2px 0 0 0;
            font-size: 13px;
            font-weight: normal;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin-bottom: 8px;
        }
        .info-item {
            margin-bottom: 3px;
        }
        .info-item strong {
            color: #2e7d32;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
            font-size: 11px;
        }
        th {
            background-color: #e8f5e9;
            border: 1px solid #ddd;
            padding: 4px 5px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 4px 5px;
        }
        .total-row {
            background-color: #e8f5e9;
            font-weight: bold;
        }
        
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 15px;
            font-size: 10px;
        }
        .signature-box {
            border-top: 1px solid #000;
            padding-top: 3px;
            text-align: center;
        }
        
        .footer {
            margin-top: 12px;
            font-size: 9px;
            text-align: center;
            color: #666;
        }
        .warning {
            margin-top: 8px;
            padding: 4px;
            font-size: 9px;
            color: #d32f2f;
            border: 1px solid #ffcdd2;
            background-color: #ffebee;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>EVOBIKE S.A DE C.V</h1>
        <h2>Formulario de Emisión de Fábrica</h2>
    </div>

    <div class="info-grid">
        <div class="info-item"><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</div>
        <div class="info-item"><strong>Pedido #:</strong> {{ $pedido->id_pedido ?? 'N/A' }}</div>
        <div class="info-item"><strong>Sucursal:</strong> {{ $pedido->sucursal->nombre_sucursal ?? 'N/D' }}</div>
        <div class="info-item"><strong>Transporte:</strong> {{ $pedido->transporte ?? 'Evobike' }}</div>
        <div class="info-item"><strong>Responsable:</strong> {{ $pedido->responsable ?? 'N/A' }}</div>
        <div class="info-item"><strong>Teléfono:</strong> {{ $pedido->telefono_chofer ?? 'N/A' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:25%">N° Serie</th>
                <th style="width:25%">Modelo</th>
                <th style="width:20%">Color</th>
                <th style="width:25%">Especificaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->bicicletas as $index => $bicicleta)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $bicicleta->num_chasis }}</td>
        <td>{{ $bicicleta->modelo->nombre_modelo ?? 'N/D' }}</td>
        <td>{{ $bicicleta->color->nombre_color ?? 'N/D' }}</td>
        <td>
            {{ $bicicleta->voltaje ?? '' }}
            {{ $bicicleta->observaciones ?? '' }}
        </td>
    </tr>
@endforeach

            <tr class="total-row">
                <td colspan="4"><strong>TOTAL BICICLETAS</strong></td>
                <td><strong>{{ count($pedido->bicicletas) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-grid">
        <div class="signature-box">Responsable de fábrica</div>
        <div class="signature-box">Inspector de calidad</div>
        <div class="signature-box">Chofer/Transportista</div>
    </div>

    <div class="footer">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y ') }}</p>
        <p>Contacto: {{ $pedido->sucursal->telefono ?? '56 4899 6759' }}</p>
    </div>

    <div class="warning">
        El pedido debe ser verificado antes de firmar. EVOBIKE no se hace responsable por daños después de la firma.
    </div>

</body>
</html>