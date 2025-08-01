<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Piezas - {{ $pedido->id_pedido }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 5px; }
        .header-logo { text-align: center; }
        .title { font-weight: bold; font-size: 18px; font-style: italic; text-align: center; padding: 8px 0; }
        .center { text-align: center; }
        .merged { background-color: #f8f8f8; }
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            font-family: 'Georgia', 'Times New Roman', serif;
            border-top: 1px solid #e8e8e8;
            font-size: 8.5pt;
            color: #777;
            text-align: center;
        }
        .emision { font-weight: bold; font-style: italic; text-align: center; }
    </style>
</head>
<body>

<!-- ENCABEZADO -->
<table class="table">
    <tr>
        <td colspan="7" class="header-logo">
            <img src="{{ public_path('images/logo.webp') }}" style="height: 45px;" alt="Logo">
        </td>
    </tr>
    <tr>
        <td colspan="7" class="title">Pedido de Piezas</td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <strong>Fecha:</strong><br>
            {{ $pedido->fecha_envio->format('d/m/Y') }}
        </td>
        <td colspan="2" class="center">
            <strong>Código:</strong><br>
            {{ $pedido->id_pedido }}
        </td>
        <td colspan="3" class="center">
            <strong>Cliente:</strong><br>
            {{ $pedido->sucursal->nombre_sucursal ?? 'N/D' }}
        </td>
    </tr>
</table>

@php
    $piezasConModelo = collect();
    $piezasSinModelo = collect();
    $modelosAgrupados = [];

    foreach ($pedido->piezas as $detalle) {
        if (!empty($detalle->pieza->modelo)) {
            $nombreModelo = $detalle->pieza->modelo->nombre_modelo;
            $piezasConModelo->push($detalle);
            $modelosAgrupados[$nombreModelo] = ($modelosAgrupados[$nombreModelo] ?? 0) + 1;
        } else {
            $piezasSinModelo->push($detalle);
        }
    }

    $piezasConModelo = $piezasConModelo->groupBy(fn($d) => $d->pieza->modelo->nombre_modelo);
    $todasLasPiezas = $piezasConModelo->flatten(1)->merge($piezasSinModelo);
@endphp

<!-- DETALLE DE PIEZAS -->
<table class="table">
    <thead>
        <tr>
            <th class="center">#</th>
            <th class="center">Modelo</th>
            <th class="center">Nombre del Accesorio</th>
            <th class="center">Color / Distinción</th>
            <th class="center">Cantidad</th>
            <th class="center">Unidad</th>
            <th class="center">Observación</th>
        </tr>
    </thead>
    <tbody>
    @php $modeloMostrado = []; @endphp
    @foreach($todasLasPiezas as $i => $detalle)
        @php
            $modelo = $detalle->pieza->modelo->nombre_modelo ?? 'Sin Modelo';
            $nombre = $detalle->pieza->nombre_pieza ?? 'N/D';
        @endphp
        <tr>
            <td class="center">{{ $i + 1 }}</td>

            @if($modelo === 'Sin Modelo')
                <td colspan="2" class="center merged">{{ $nombre }}</td>
            @else
                @if(!isset($modeloMostrado[$modelo]))
                    <td rowspan="{{ $modelosAgrupados[$modelo] }}" class="center">{{ $modelo }}</td>
                    @php $modeloMostrado[$modelo] = true; @endphp
                @endif
                <td class="center">{{ $nombre }}</td>
            @endif

            <td class="center">{{ $detalle->pieza->color ?? 'N/A' }}</td>
            <td class="center">{{ $detalle->cantidad }}</td>
            <td class="center">{{ $detalle->pieza->Unidad ?? 'Pz' }}</td>
            <td class="center">/</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- SECCIÓN DE NOTAS Y FIRMAS -->
<table class="table">
    <tr>
        <td style="width: 59%; height: 90px; font-style: italic; text-align: center; font-weight: bold;">
            Este pedido es por duplicado, uno se enviará al destino con la mercancía, otro se guardará en fábrica y el archivo electrónico se enviará al departamento comercial.
        </td>
        <td rowspan="2" style="width: 41%; vertical-align: top; font-style: italic; text-align: center; font-weight: bold;">
            Sello o firma del responsable de fábrica:
        </td>
    </tr>
    <tr>
        <td style="padding: 2px; font-size: 10px; height: 20px; line-height: 1;">
            Firma del inspector de calidad:
        </td>
    </tr>
</table>

<table class="table">
    <tr>
        <td style="width: 40%; padding: 5px;">
            Firma del chofer:<br>
        </td>
        <td style="width: 60%; padding: 5px;">
            Teléfono chofer:<br>
        </td>
    </tr>
</table>

<table class="table">
    <tr>
        <td class="emision">Recibo de Emisión</td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse; border-left: 1px solid black; border-right: 1px solid black;">
    <tr style="border-bottom: 1px solid black;">
        <td style="width: 33%; padding: 5px;">Verificación de orden de emisión</td>
        <td style="width: 33%; padding: 5px;">Verificado</td>
        <td style="width: 33%; padding: 5px;">Error de verificarlo</td>
    </tr>
</table>

<table class="table">
    <tr>
        <td class="center">
            Firma del responsable de la tienda (el recibo se recibirá tras confirmar el pedido):
        </td>
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
            El pedido deberá ser supervisado por el cliente, una vez firmado este documento la empresa no se hace responsable.
        </td>
    </tr>
</table>

<!-- PIE DE PÁGINA -->
<div class="footer">
    Powered by CloudLabs <img src="{{ public_path('images/CloudLabs.png') }}" style="height: 12px; vertical-align: middle;">
</div>

</body>
</html>
