<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización {{ $codigo }}</title>
  <style>
    body { 
    font-family: 'Arial', sans-serif; 
    font-size: 12px; 
    margin: 0; 
    padding: 20px; 
    color: #333;
    position: relative;
    z-index: 1;
  }
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-bottom: 15px; 
    }
    th, td { 
      border: 1px solid #ddd; 
      padding: 8px; 
    }
    th { 
      background-color: #f8f9fa; 
      font-weight: 600;
      color: #333;
    }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .no-border, .no-border th, .no-border td { border: none; }
    .highlight { background-color: #FFFF00; }
    .total-row { background-color: #f8f9fa; }
    .header-title {
      font-size: 18px;
      font-weight: bold;
      color: #000000ff;
      margin: 0;
      padding: 5px 0;
    }
    .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e8e8e8;
            font-size: 8.5pt;
            color: #777;
            text-align: center;
        }
    .company-info {
      font-size: 10px;
      line-height: 1.4;
    }
    .note-box {
      background-color: #f8f9fa;
      padding: 8px;
      border-radius: 4px;
      font-size: 10px;
    }
    .signature-line {
      border-top: 1px solid #333;
      width: 60%;
      margin: 0 auto;
      padding-top: 5px;
    }
    body::before {
    content: "";
    position: fixed;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background-image: url('{{ public_path("images/logo.jpg") }}');
    background-repeat: repeat;
    background-size: 100px;
    transform: rotate(20deg);
    opacity: 0.05;
    z-index: 0;
    pointer-events: none;
  }
    
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <table class="no-border">
    <tr>
      <td style="width: 15%; text-align: center; vertical-align: middle;">
        <img src="{{ public_path('images/favico.ico') }}" alt="Logo" height="50">
      </td>
      <td style="text-align: center; vertical-align: middle;">
        <div class="header-title">COTIZACIÓN DE PEDIDOS</div>
        <div style="font-size: 11px; color: #666;">###{{ $codigo }}###</div>
      </td>
      <td style="width: 15%; text-align: center; vertical-align: middle;">
        <img src="{{ public_path('images/favico.ico') }}" alt="Logo" height="50">
      </td>
    </tr>
  </table>

  <!-- DATOS DEL CLIENTE -->
  <table>
    <tr>
      <th style="width: 15%; ">NOMBRE:</th>
      <td style="width: 35%;">{{ $cliente->nombre }}</td>
      <th style="width: 10%;">FECHA:</th>
      <td style="width: 20%;">{{ $fecha }}</td>
    </tr>
    <tr>
      <th>ASESOR:</th>
      <td>{{ $asesor }}</td>
      <th>TELÉFONO:</th>
      <td>{{ $cliente->telefono }}</td>
    </tr>
    <tr>
      <th>DIRECCIÓN:</th>
      <td colspan="3" style="text-align: center;">{{ $cliente->direccion }}</td>
    </tr>
  </table>

  <!-- TABLA DE PRODUCTOS -->
  <table>
    <thead>
      <tr>
        <th style="width: 8%;">Cantidad</th>
        <th style="width: 22%;">Modelo</th>
        <th style="width: 10%;">Voltaje</th>
        <th style="width: 12%;">Color</th>
        <th style="width: 15%;">P. Unitario</th>
        <th style="width: 15%;">Subtotal</th>
        <th style="width: 18%;">Método Entrega</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lineas as $l)
      <tr>
        <td class="text-center">{{ $l->cantidad }}</td>
        <td class="text-center">{{ $l->modelo }}</td>
        <td class="text-center">{{ $l->voltaje }}</td>
        <td class="text-center">{{ $l->color }}</td>
        <td class="text-right">${{ number_format($l->precio, 2) }}</td>
        <td class="text-right">${{ number_format($l->subtotal, 2) }}</td>
        <td class="text-center">{{ $l->metodo_entrega }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <!-- TOTALES Y DATOS EMPRESA -->
 <!-- TOTALES Y DATOS EMPRESA -->
<table class="no-border">
  <tr>
    <td style="width: 60%; vertical-align: top; text-align: center;">
      <div class="company-info" style="display: inline-block; text-align: center;">
        <strong>EVOBIKE S.A. DE C.V.</strong><br>
        RFC: EVO2306138ZA<br>
        Carretera México-Puebla Kilometro 27.5<br>
        Bodega 6, Ixtapaluca, Edo. Méx.<br>
        Tel: 55 3059 6304
      </div>
    </td>
    <td style="width: 40%; vertical-align: top;">
      <table style="width: 100%;">
        <tr class="total-row">
          <td class="text-right"><strong>Subtotal:</strong></td>
          <td class="text-right">${{ number_format($total, 2) }}</td>
        </tr>
        <tr class="total-row">
          <td class="text-right"><strong>IVA (8%):</strong></td>
          <td class="text-right">${{ number_format($iva, 2) }}</td>
        </tr>
        <tr class="highlight">
          <td class="text-right"><strong>Total a Pagar:</strong></td>
          <td class="text-right"><strong>${{ number_format($total + $iva, 2) }}</strong></td>
        </tr>
      </table>
    </td>
  </tr>
</table>


  <!-- NOTA -->
  <div class="note-box">
    <strong>NOTA:</strong> El cliente se compromete a recolectar y liquidar los vehículos en un máximo de 30 días a partir de la fecha de cotización. Esta cotización es válida por 7 días naturales.
  </div>

 
<div style="text-align:center; margin: 70px 0;">
    <img src="data:image/svg+xml;base64,{{ $qr_base64 }}" alt="QR de Cotización" style="width: 150px; height: 150px;">
</div>


  <div class="footer" style="display: flex; align-items: center; height: 50px; position: relative;">
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
            <img src="{{ public_path('images/CloudLabs.png') }}" 
                 style="height: 15px; width: auto; display: block; position: relative; top: 3px; margin-left: 1px;">
        </div>

</body>
</html>