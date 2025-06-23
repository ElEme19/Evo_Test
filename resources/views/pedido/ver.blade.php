@extends('layout.app')

@section('conten-wrapper')
<style>
    /* Reduce paddings verticales en la tabla para filas compactas */
    .table > :not(caption) > * > * {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }

    /* Reduce margen inferior de encabezados para que queden más juntos */
    thead th {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }

    /* Reduce margen de títulos dentro del header */
    header.d-flex h1.h3 {
        margin-bottom: 0.25rem !important;
    }

    /* Reduce margen de alertas */
    .alert-container {
        margin-bottom: 0.75rem !important;
    }

    /* Reduce padding en la card-header */
    .card-header {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    /* Reduce padding en el card-footer */
    .card-footer {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    /* Reduce espacio entre líneas para que texto se vea más compacto */
    .table-hover tbody tr {
        line-height: 1.1 !important;
    }
</style>

<div class="container px-0 px-md-3 mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <!-- Encabezado con título y botón -->
            <header class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                <h1 class="h3 mb-0 text-primary fw-bold">
                    <i class="bi bi-truck me-2"></i>Gestión de Pedidos
                </h1>
                <a href="{{ route('pedido.crear') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Pedido
                </a>
            </header>

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

            <!-- Tarjeta de tabla -->
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
                                                {{ \App\Models\Pedidos::where('id_pedido', $pedidoGroup->id_pedido)->count() }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                                            {{ $pedidoGroup->fecha_envio->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $pedidoGroup->fecha_envio->format('H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('pedido.pdf', $pedidoGroup->id_pedido) }}" 
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               data-bs-toggle="tooltip" 
                                               title="Generar PDF">
                                                <i class="bi bi-filetype-pdf me-1"></i>PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center text-muted">
                                                <i class="bi bi-box-seam display-5 mb-3"></i>
                                                <span>No hay pedidos registrados</span>
                                                <a href="{{ route('pedido.crear') }}" class="btn btn-link mt-2">
                                                    Crear primer pedido
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pie de tabla con paginación -->
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
