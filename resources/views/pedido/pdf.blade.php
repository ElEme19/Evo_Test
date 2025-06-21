<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido {{ $pedidos->first()->id_pedido ?? 'Pedido' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h4 {
            text-align: center;
            margin-bottom: 0.5em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1em;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
        .info {
            margin-top: 1em;
        }
        .info strong {
            display: inline-block;
            width: 120px;
        }
    </style>
</head>
<body>

    <h2>Pedido de Bicicletas</h2>
    <h4>ID Pedido: {{ $pedidos->first()->id_pedido ?? '' }}</h4>

    <div class="info">
        <p><strong>Sucursal:</strong> {{ $pedidos->first()->sucursal->nombre ?? 'N/A' }}</p>
        <p><strong>Fecha de Envío:</strong> {{ $pedidos->first()->fecha_envio ? \Carbon\Carbon::parse($pedidos->first()->fecha_envio)->format('d/m/Y H:i') : 'N/A' }}</p>
        <p><strong>Total Bicicletas:</strong> {{ $pedidos->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Número de Serie</th>
                <th>Modelo</th>
                <th>Color</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $index => $pedido)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pedido->num_chasis }}</td>
                    <td>{{ $pedido->bicicleta->modelo->nombre_modelo ?? 'N/D' }}</td>
                    <td>{{ $pedido->bicicleta->color->nombre_color ?? 'N/D' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
