<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #444; padding: 4px; }
    th { background: #eee; }
    .text-right { text-align: right; }
    .footer {
      margin-top: 25px;
      padding-top: 12px;
      font-family: 'Georgia', 'Times New Roman', serif;
      border-top: 1px solid #e8e8e8;
      font-size: 8.5pt;
      color: #777;
      text-align: center;
    }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Cotización</h2>
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Modelo</th>
      <th>Color</th>
      <th>Voltaje</th>
      <th>Cantidad</th> <!-- nueva columna -->
      <th class="text-right">Precio Unitario</th>
      <th class="text-right">Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($lineas as $i => $l)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $l->modelo }}</td>
        <td>{{ $l->color }}</td>
        <td>{{ $l->voltaje }}</td>
        <td>{{ $l->cantidad }}</td> <!-- mostrar cantidad -->
        <td class="text-right">{{ '$ ' . number_format($l->precio, 2, '.', ',') }}</td>
        <td class="text-right">{{ '$ ' . number_format($l->subtotal, 2, '.', ',') }}</td>
      </tr>
    @endforeach
  </tbody>
    <tfoot>
      <tr>
        <th colspan="6" class="text-right">Total</th>
        <th class="text-right">{{ '$ ' . number_format($total, 2, '.', ',') }}</th>
      </tr>
    </tfoot>
  </table>

  <div class="footer" style="display: flex; align-items: center; height: 22px; position: relative;">
   
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#4a6baf" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 5px;">
      <path d="M8 1a5.53 5.53 0 0 0-3.594 1.343A5.49 5.49 0 0 0 8 0a5.49 5.49 0 0 0 3.594 2.343A5.53 5.53 0 0 0 8 1z"/>
      <path d="M4.406 3.3C2.664 4.045 1.5 5.897 1.5 8c0 2.485 2.015 4.5 4.5 4.5H11a4.5 4.5 0 0 0 0-9 5.53 5.53 0 0 0-3.594 1.3z"/>
    </svg>

    <span style="line-height: 22px;">Powered By: CloudLabs</span>

    
    <img src="{{ public_path('images/CloudLabs.png') }}"
         style="height: 15px; width: auto; display: block; position: relative; top: 3px; margin-left: 5px;">
  </div>
</body>
</html>
