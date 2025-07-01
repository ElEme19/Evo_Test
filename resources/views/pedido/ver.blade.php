@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Estilos compactos consistentes */
  .table td, .table th {
    padding: .3rem .5rem !important;
  }
  
  .card-header, .card-footer {
    padding: .75rem 1rem !important;
  }
  
  .table-hover tbody tr {
    line-height: 1.2 !important;
  }
  
  /* Efectos hover y focus */
  .btn-outline-success:hover {
    background-color: #198754;
    color: white;
  }
  
  .form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
  }
  
  /* Estilo para filas expandibles */
  .collapse-row {
    background-color: #f8f9fa;
    border-bottom: 1rem solid white !important; /* Espacio adicional debajo del detalle */
  }
  
  /* Badge moderno */
  .badge-count {
    font-size: 0.85rem;
    padding: 0.35em 0.65em;
  }
  
  /* Separación entre pedidos */
  .pedido-row {
    margin-bottom: 1.5rem !important;
    border-bottom: 2px solid #f0f0f0 !important;
  }
  
  /* Espacio en tabla de detalles */
  .tabla-detalle {
    margin-bottom: 1.5rem;
  }
  
  /* Espacio en el detalle expandido */
  .detalle-expandido {
    padding-bottom: 1.5rem !important;
  }
</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <!-- HEADER PRINCIPAL MEJORADO -->
      <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
        <!-- TÍTULO E ICONO -->
        <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
          <div class="me-3 p-2 rounded-circle bg-success bg-opacity-10">
            <i class="bi bi-truck text-success fs-3"></i>
          </div>
          <div>
            <h1 class="h4 fw-bold mb-0">Gestión de Pedidos</h1>
            <p class="text-muted small mb-0">Administración de envíos de bicicletas</p>
          </div>
        </div>

        <!-- BÚSQUEDA + ACCIÓN -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 w-100 w-md-auto" style="max-width: 500px;">
          <!-- Formulario de búsqueda compacto -->
          <form action="{{ route('pedido.buscar') }}" method="GET" class="flex-grow-1 position-relative">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0 rounded-start">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" name="q" value="{{ request('q') }}" 
                     class="form-control border-start-0 rounded-end" 
                     placeholder="Buscar pedido o sucursal">
              <button type="submit" class="btn btn-sm position-absolute end-0 top-0 h-100 rounded-end px-3 d-none d-sm-block" 
                      style="background-color: transparent; color: #6c757d;">
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </form>
          
          <!-- Botón NUEVO PEDIDO -->
          <a href="{{ route('pedido.crear') }}"
             class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow-sm py-2 px-3 rounded">
            <i class="bi bi-plus-circle-fill me-2"></i>
            <span>Nuevo Pedido</span>
          </a>
        </div>
      </header>

      <!-- ALERTAS -->
      @if(isset($busqueda) && $busqueda)
        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
          <i class="bi bi-search me-2"></i>Mostrando resultados para: <strong>{{ $busqueda }}</strong>
          <a href="{{ route('pedido.ver') }}" class="btn btn-sm btn-link">Limpiar</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm mb-3" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <small class="fw-semibold">{{ session('success') }}</small>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm mb-3" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <small class="fw-semibold">{{ session('error') }}</small>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- PDF Handler (sin cambios) -->
      @if(session('pdf_url'))
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const pdfUrl = "{{ session('pdf_url') }}";
          window.open(pdfUrl, '_blank');
        });
      </script>
      @endif

      <!-- TARJETA PRINCIPAL -->
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
                  <th class="fw-semibold">ID Pedido</th>
                  <th class="fw-semibold">Sucursal</th>
                  <th class="fw-semibold text-center">Bicicletas</th>
                  <th class="fw-semibold text-center">Fecha Envío</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($pedidos as $index => $pedidoGroup)
                  <!-- Fila principal del pedido -->
                  <tr class="pedido-row">
                    <td class="text-muted fw-semibold">#{{ $pedidoGroup->id_pedido }}</td>
                    <td>
                      <span class="d-block fw-semibold">{{ $pedidoGroup->sucursal->nombre_sucursal ?? 'N/A' }}</span>
                      <small class="text-muted">{{ $pedidoGroup->sucursal->direccion ?? '' }}</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-primary rounded-pill badge-count">
                        {{ $pedidoGroup->bicicletas->count() ?? 0 }}
                      </span>
                    </td>
                    <td class="text-center text-nowrap">
                      <i class="bi bi-calendar-event me-1 text-muted"></i>
                      {{ $pedidoGroup->fecha_envio->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#detalle-{{ $index }}">
                          <i class="bi bi-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditar{{ $pedidoGroup->id_pedido }}">
                          <i class="bi bi-pencil-square me-1"></i>Editar
                        </button>
                        <a href="{{ route('pedido.pdf', $pedidoGroup->id_pedido) }}" 
                           class="btn btn-outline-danger btn-sm rounded-pill px-3">
                          <i class="bi bi-filetype-pdf me-1"></i>PDF
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- DETALLE BICICLETAS (con más espacio) -->
                  <tr class="collapse collapse-row detalle-expandido" id="detalle-{{ $index }}">
                    <td colspan="5" class="p-3">
                      <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-bicycle text-muted me-2"></i>
                        <h6 class="mb-0 text-muted">Bicicletas en el pedido <strong>#{{ $pedidoGroup->id_pedido }}</strong></h6>
                      </div>
                      
                      <div class="table-responsive tabla-detalle">
                        <table class="table table-sm table-bordered align-middle mb-3">
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
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $bici->num_chasis }}</td>
                                <td>{{ $bici->modelo->nombre_modelo ?? 'N/D' }}</td>
                                <td>{{ $bici->color->nombre_color ?? 'N/D' }}</td>
                                <td>{{ $bici->voltaje->tipo_voltaje ?? 'Sin Voltaje' }}</td>
                                <td><small>{{ $bici->observaciones ?? '-' }}</small></td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
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