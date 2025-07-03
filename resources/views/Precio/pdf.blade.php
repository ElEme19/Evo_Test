<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Precios Evobike</title>
    

    <style>
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 10.5pt;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #fff;
            position: relative;
            z-index: 1;
        }

        /* Marca de agua en todas las páginas usando pseudo-elemento */
        body::before {
            content: "";
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background-image: url('{{ public_path("images/logo.jpg") }}');
            background-repeat: repeat;
            background-size: 100px; /* Puedes ajustar el tamaño según el logo */
            transform: rotate(20deg);
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
        }


        .content {
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e8e8e8;
        }

        .title {
            font-size: 16pt;
            font-weight: 400;
            letter-spacing: 1px;
            color: #222;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 9.5pt;
            color: #666;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-top: 15px;
            border: 1px solid #e0e0e0;
        }

        th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 500;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #e0e0e0;
            letter-spacing: 0.5px;
        }

        td {
            padding: 7px 6px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .model-cell {
            font-weight: 500;
            background-color: rgba(250, 250, 250, 0.7); /* transparente para ver la marca de agua */
            text-align: center;
            color: #333;
        }

        .voltage-cell {
            font-weight: 400;
            color: #444;
        }

        .price-cell {
            font-weight: 400;
            color: #222;
        }

        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e8e8e8;
            font-size: 8.5pt;
            color: #777;
            text-align: center;
        }

        @page {
            size: A4 ; /*  landscape*/
            margin: 0.5cm 1cm 1cm 1cm; /* arriba, derecha, abajo, izquierda */
        }

        @media print {
            body { padding: 0; }
            .header { margin-top: 0; padding-top: 0; }
            table { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <div class="title">Lista de Precios</div>
            @php
                use Carbon\Carbon;
                Carbon::setLocale('es');
                $inicioSemana = Carbon::now();
            @endphp
            <div class="subtitle">Vigente desde {{  $inicioSemana->translatedFormat('d F Y') }}</div>
        </div>


<!-- 
        <div class="header">
    <div class="title">Lista de Precios</div>
    @php
        
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();
    @endphp
    <div class="subtitle">
        Vigente del {{ $inicioSemana->translatedFormat('d') }} al {{ $finSemana->translatedFormat('d F Y') }}
    </div>
</div>
 -->


        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Modelo</th>
                    <th style="width: 15%;">Voltaje</th>
                    @foreach($membresias as $m)
                        <th>{{ $m->descripcion_general }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $porModelo = $precios->groupBy('id_modelo'); @endphp
                @foreach($porModelo as $modelId => $grupoModelo)
                    @php
                        $modelo = $grupoModelo->first()->modelo;
                        $voltajes = $grupoModelo->groupBy('id_voltaje');
                        $rowspan = $voltajes->count();
                        $first = true;
                    @endphp
                    @foreach($voltajes as $voltajeId => $grupoVoltajes)
                        @php $volt = $grupoVoltajes->first()->voltaje; @endphp
                        <tr>
                            @if($first)
                                <td class="model-cell" rowspan="{{ $rowspan }}">{{ $modelo->nombre_modelo ?? '' }}</td>
                                @php $first = false; @endphp
                            @endif
                            <td class="voltage-cell">{{ $volt->tipo_voltaje ?? $volt->voltaje ?? '' }}</td>
                            @foreach($membresias as $m)
                                @php $p = $grupoVoltajes->firstWhere('id_membresia', $m->id_membresia); @endphp
                                <td class="price-cell">{{ $p ? '$' . number_format($p->precio, 2) : '------' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="footer" style="display: flex; align-items: center; height: 22px; position: relative;">
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
    </div>
</body>
</html>
