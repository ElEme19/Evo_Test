<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Pedido {{ $pedido->id_pedido }}</title>
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
        .small { font-size: 9px; }
        .center { text-align: center; }
        .no-border { border: none !important; }
        .footer-note { font-size: 8px; }
        .footer-red { color: red; font-size: 8px; }
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
            <td style="text-align: center;"><strong>Fecha:</strong><br>{{ now()->format('d/m/Y') }}</td>
            <td style="text-align: center;"><strong>Código:</strong><br>{{ $pedido->id_pedido }}</td>
            <td style="text-align: center;"><strong>Cliente:</strong><br>{{ $pedido->sucursal->nombre_sucursal }}</td>
            <td style="text-align: center;"><strong>Distancia:</strong><br>{{ $pedido->sucursal->distancia_km ?? 'N/D' }} KM</td>
            <td style="text-align: center;"><strong>Transporte:</strong><br>Evobike</td>
            <td style="text-align: center;"><strong>Costo Envío:</strong><br>{{ $pedido->costo_envio ?? '0.00' }}</td>
        </tr>
    </table>

    <!-- Detalle de bicis -->
    <table class="table">
        <thead>
            <tr>
                <th class="small center" style="width:5%;">#</th>
                <th class="small" style="width:23%;">Modelo</th>
                <th class="small" style="width:21%;">Color</th>
                <th class="small center" style="width:10%;">Cantidad</th>
                <th class="small" style="width:25%;">No. Frame</th>
                <th class="small" style="width:16%;">Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->bicicletas as $i => $bic)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td style="text-align: center;">{{ $bic->modelo->nombre_modelo ?? 'N/D' }}</td>
                <td style="text-align: center;">{{ $bic->color->nombre_color ?? 'N/D' }}</td>
                <td class="center">1</td>
                <td style="text-align: center;">{{ $bic->num_chasis }}</td>
                <td style="text-align: center;">{{ $bic->voltaje}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Firmas y notas -->
    <table class="table header-table">
    <tr>
    <td style="width: 59%; height: 90px;  font-size: 11px; padding: 4px;  font-style: italic; text-align: center; font-weight: bold;">
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



</body>
</html>
