@extends('layout.app')

@section('conten-wrapper')
<style>
    :root {
        --primary-color: #4DB53F;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition-speed: 0.3s;
    }
    
    .table-container {
        overflow: hidden;
        border-radius: 0.5rem;
    }
    
    .table thead th {
        background-color: var(--primary-color);
        color: white;
        padding: 0.75rem 1rem;
        font-weight: 500;
    }
    
    .table tbody tr {
        transition: all var(--transition-speed) ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(77, 181, 63, 0.05);
    }
    
    .table-hover tbody tr {
        line-height: 1.25;
    }
    
    .badge-count {
        min-width: 30px;
        display: inline-block;
        text-align: center;
    }
    
    .btn-action {
        transition: all var(--transition-speed) ease;
        padding: 0.375rem 0.75rem;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .collapse-row {
        background-color: var(--light-bg);
    }
    
    .empty-state {
        padding: 3rem 0;
    }
    
    .search-box {
        max-width: 300px;
        transition: all var(--transition-speed) ease;
    }
    
    .search-box:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(77, 181, 63, 0.25);
    }
    
    .card-main {
        border: none;
        box-shadow: var(--card-shadow);
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }
    
    .alert-container {
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid px-lg-4 px-xl-5 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-truck text-success me-3 fs-3"></i>
                    <h1 class="h3 mb-0 text-dark fw-bold">Gestión de Pedidos</h1>
                </div>
                
                <div class="d-flex gap-3 align-items-center flex-wrap">
                    <form action="{{ route('pedido.buscar') }}" method="GET" class="position-relative">
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Buscar pedido o sucursal..."
                               class="form-control search-box ps-4 rounded-pill">
                        <i class="fas fa-search position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
                    </form>
                    <a href="{{ route('pedido.crear') }}" class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Pedido
                    </a>
                </div>
            </div>

            <!-- Search Alert -->
            @if(isset($busqueda) && $busqueda)
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-search me-2"></i>
                    <div>Mostrando resultados para: <strong>{{ $busqueda }}</strong></div>
                    <a href="{{ route('pedido.ver') }}" class="btn btn-sm btn-link ms-2">Limpiar</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <!-- System Alerts -->
            <div class="alert-container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Main Card -->
            <div class="card card-main mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0 d-flex align-items-center">
                        <i class="fas fa-clipboard-list text-secondary me-2"></i>
                        Pedidos Registrados
                    </h2>
                </div>

                <div class="table-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Sucursal</th>
                                <th class="text-center">Bicicletas</th>
                                <th>Fecha Envío</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pedidos as $index => $pedidoGroup)
                                <tr class="border-top">
                                    <td class="fw-semibold">#{{ $pedidoGroup->id_pedido }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $pedidoGroup->sucursal->nombre_sucursal ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $pedidoGroup->sucursal->direccion ?? '' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill badge-count">
                                            {{ $pedidoGroup->bicicletas->count() ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                        {{ $pedidoGroup->fecha_envio->format('d/m/Y') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-outline-primary btn-action rounded-pill" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#detalle-{{ $index }}">
                                                <i class="fas fa-eye me-1"></i>Detalles
                                            </button>
                                            <a href="{{ route('pedido.pdf', $pedidoGroup->id_pedido) }}" 
                                               class="btn btn-sm btn-outline-danger btn-action rounded-pill">
                                                <i class="fas fa-file-pdf me-1"></i>PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Bike Details Row -->
                                <tr class="collapse collapse-row" id="detalle-{{ $index }}">
                                    <td colspan="5" class="p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bicycle text-muted me-2"></i>
                                            <h6 class="mb-0 text-dark">Bicicletas del pedido #{{ $pedidoGroup->id_pedido }}</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
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
                                                    @foreach ($pedidoGroup->bicicletas as $i => $bici)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td class="fw-medium">{{ $bici->num_chasis }}</td>
                                                            <td>{{ $bici->modelo->nombre_modelo ?? 'N/D' }}</td>
                                                            <td>{{ $bici->color->nombre_color ?? 'N/D' }}</td>
                                                            <td>{{ $bici->voltaje ?? '-' }}</td>
                                                            <td>{{ $bici->observaciones ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center empty-state">
                                        <div class="d-flex flex-column align-items-center text-muted py-4">
                                            <i class="fas fa-box-open fs-1 mb-3"></i>
                                            <p class="mb-2">No hay pedidos registrados</p>
                                            <a href="{{ route('pedido.crear') }}" class="btn btn-success rounded-pill px-4">
                                                <i class="fas fa-plus me-2"></i>Crear primer pedido
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pedidos->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-center">
                            {{ $pedidos->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome Kit -->
<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
@endsection