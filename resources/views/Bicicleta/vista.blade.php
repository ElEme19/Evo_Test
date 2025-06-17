@extends('layout.app')

@section('title')

@section('conten-wrapper')
<div class="container-fluid mt-4 px-3">
    
</div>


<div class="text-center my-4">
    <h3>
        Bicicletas Registradas
        <span class="badge rounded-pill text-bg-success">Ver</span>
    </h3>
        

@include('Busquedas.busChasis')
@include('Busquedas.busMotor')
@include('Busquedas.busModelo')
@include('Busquedas.busStock')



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<div class="container mt-4">
<table class="table table-bordered table-hover mt-5" id="tablaBicicletas">
    <thead class="table-light">
        <!-- Fila de íconos de búsqueda arriba de los encabezados -->
        <tr>
        <th scope="col" class="text-center">
            Num. Serie
            <i class="bi bi-search text-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalBuscarBici" title="Buscar por Num. Serie" style="cursor: pointer;"></i>
        </th>
        <th scope="col" class="text-center">
            Motor
            <i class="bi bi-search  text-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalBuscarBiciMotor" title="Buscar por Motor" style="cursor: pointer;"></i>
        </th>
        <th scope="col" class="text-center">
            Modelo
            <i class="bi bi-search  text-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalBuscarBiciModelo" title="Buscar por Modelo" style="cursor: pointer;"></i>
        </th>
        <th scope="col" class="text-center">
            Color
        </th>
        <th scope="col" class="text-center">
            Voltaje
        </th>
        <th scope="col" class="text-center">
            Stock
            <i class="bi bi-search  text-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalBuscarBiciStock" title="Buscar por Stock" style="cursor: pointer;"></i>
        </th>
        </tr>
    </thead>

    @if($bicicletas->isEmpty())
    <tr>
        <td colspan="8" class="text-center">No hay bicicletas registradas.</td>
    </tr>
@endif

    <tbody>
        @foreach($bicicletas as $bici)
        <tr>
            <td class="text-center" >{{ $bici->num_chasis }}</td>
            <td class="text-center" >{{ $bici->num_motor ?? 'N/A' }}</td>
            <td class="text-center" >{{ $bici->modelo->nombre_modelo ?? 'N/A' }}</td>
            <td class="text-center" >{{ $bici->color->nombre_color ?? 'N/A' }}</td>
            <td class="text-center" >{{ $bici->voltaje ?? 'N/A' }}</td>
            <!-- <td>
                {{ $bici->lote && $bici->lote->fecha_produccion
                    ? \Carbon\Carbon::parse($bici->lote->fecha_produccion)->format('d-m-Y')
                    : 'N/A'
                }}
                </td> -->
            <td class="text-center" >[{{ $bici->tipoStock->nombre_stock ?? 'N/A' }}]</td>

            <!-- <td>
                <button type="button" 
                        class="btn btn-outline-success btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalActualizar{{ $bici->num_chasis  }}">
                    Actualiza
                </button>
                 
            </td> -->
        </tr>
        @endforeach
    </tbody>
</table>
</div>


@endsection
