@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            
            <!-- Encabezado -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">@lang('Bicicletas Registradas')</span>
                    <span class="badge rounded-pill text-bg-success">@lang('Ver')</span>
                </h3>
            </div>

            <!-- Modales de búsqueda -->
            @include('Busquedas.busChasis')
            @include('Busquedas.busMotor')
            @include('Busquedas.busModelo')
            @include('Busquedas.busStock')

            @if (auth()->user()->rol == 0)
                <div class="text-center mb-3">
                    <a href="{{ route('Bicicleta.crear') }}" class="btn btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        @lang('Crear Nuevo Bici')
                    </a>
                </div>
            @endif

            <!-- Tabla -->
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tablaBicicletas">
                            <thead class="table-light">
                                <tr>
                                    @foreach (['Num. Serie', 'Motor', 'Modelo', 'Color', 'Voltaje', 'Stock'] as $col)
                                        <th scope="col" class="text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span>@lang($col)</span>
                                                @if (!in_array($col, ['Color', 'Voltaje']))
                                                    <i class="bi bi-search text-primary ms-2"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modalBuscarBici{{ $col == 'Num. Serie' ? '' : $col }}"
                                                       title="@lang('Buscar por') @lang($col)"
                                                       style="cursor: pointer;"></i>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($bicicletas as $bici)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $bici->num_chasis }}</td>
                                    <td class="text-center">{{ $bici->num_motor ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $bici->modelo->nombre_modelo ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ $bici->color->nombre_color ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $bici->voltaje ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge 
                                            @if(optional($bici->tipoStock)->nombre_stock == 'Fabrica') bg-success
                                            @elseif(optional($bici->tipoStock)->nombre_stock == 'Nacional') bg-danger
                                            @elseif(optional($bici->tipoStock)->nombre_stock == 'San wicho') bg-warning text-dark
                                            @else bg-info
                                            @endif">
                                            {{ $bici->tipoStock->nombre_stock ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle mb-2" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                        </svg>
                                        <p class="mb-0">@lang('No hay bicicletas registradas')</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(method_exists($bicicletas, 'links'))
                <div class="mt-3">
                    {{ $bicicletas->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recursos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
