@extends('layout.app')

@section('conten-wrapper')
<style>
    .table > :not(caption) > * > * {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
    thead th {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
    header.d-flex h1.h3 {
        margin-bottom: 0.25rem !important;
    }
    .alert-container {
        margin-bottom: 0.75rem !important;
    }
    .card-header {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    .card-footer {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    .table-hover tbody tr {
        line-height: 1.1 !important;
    }
</style>

<div class="container px-0 px-md-3 mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <!-- Encabezado con título y botones -->
            <header class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                <h1 class="h3 mb-0 text-success fw-bold">
                    <i class="bi bi-truck me-2"></i>Gestión de Pedidos
                </h1>

                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <form action="{{ route('pedido.buscar') }}" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Buscar pedido o sucursal"
                               class="form-control form-control-sm rounded-pill shadow-sm"
                               style="max-width: 230px;">
                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                            <i class="bi bi-search-heart-fill me-1"></i>Buscar
                        </button>
                    </form>

                    <a href="{{ route('pedido.crear') }}" class="btn btn-success shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Pedido
                    </a>
                </div>
            </header>

            <!-- Mensaje de búsqueda -->
            @if(isset($busqueda) && $busqueda)
                <div class="alert alert-info alert-dismissible fade show mt-1" role="alert">
                    <i class="bi bi-search me-2"></i>Mostrando resultados para: <strong>{{ $busqueda }}</strong>
                    <a href="{{ route('pedido.ver') }}" class="btn btn-sm btn-link">Limpiar</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <!-- Alertas -->
            <div class="alert-container mb-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Tabla de pedidos -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h2 class="h6 mb-0 text-secondary">
                        <i class="bi bi-list-check me-2"></i>Pedidos Registrados
                    </h2>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold text-nowrap">ID Pedido</th>
                                    <th class="fw-semibold">Sucursal</th>
                                    <th class="fw-semibold text-center">Bicicletas</th>
                                    <th class="fw-semibold text-nowrap">Fecha Envío</th>
                                    <th class="fw-semibold text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pedidos as $pedidoGroup)
    <tr class="border-top">
        <td class="text-muted">#{{ $pedidoGroup->id_pedido }}</td>
        <td>
            <span class="d-block">{{ $pedidoGroup->sucursal->nombre_sucursal ?? 'N/A' }}</span>
            <small class="text-muted">{{ $pedidoGroup->sucursal->direccion ?? '' }}</small>
        </td>
        <td class="text-center">
            <span class="badge bg-primary rounded-pill">
                {{ $pedidoGroup->bicicletas->count() ?? 0 }}
            </span>
        </td>
        <td class="text-nowrap">
            <i class="bi bi-calendar-event me-1 text-muted"></i>
            {{ $pedidoGroup->fecha_envio->format('d/m/Y') }}
        </td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#detalle-{{ $loop->index }}">
                <i class="bi bi-eye me-1"></i>Ver
            </button>
            <a href="{{ route('pedido.pdf', $pedidoGroup->id_pedido) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                <i class="bi bi-filetype-pdf me-1"></i>PDF
            </a>
        </td>
    </tr>

    <!-- Fila desplegable con bicicletas -->
    <tr class="collapse" id="detalle-{{ $loop->index }}">
        <td colspan="5" class="bg-light">
            <div class="p-3">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-bicycle me-2"></i>Bicicletas del pedido <strong>{{ $pedidoGroup->id_pedido }}</strong>
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>N° Serie</th>
                                <th>Modelo</th>
                                <th>Color</th>
                                <th>Voltaje</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidoGroup->bicicletas as $index => $bici)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $bici->num_chasis }}</td>
                                    <td>{{ $bici->modelo->nombre_modelo ?? 'N/D' }}</td>
                                    <td>{{ $bici->color->nombre_color ?? 'N/D' }}</td>
                                    <td>{{ $bici->voltaje ?? '-' }}</td>
                                    <td>{{ $bici->observaciones ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </td>
    </tr>
@endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($pedidos->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $pedidos->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
