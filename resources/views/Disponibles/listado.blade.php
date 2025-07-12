@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Estilos consistentes con la aplicación */
  .table td, .table th {
    padding: .5rem .75rem !important;
  }
  
  .card-header {
    padding: 1rem 1.25rem !important;
  }
  
  /* Efectos hover y focus */
  .btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
  }
  
  /* Badge moderno para los totales */
  .badge-total {
    font-size: 0.9rem;
    padding: 0.35em 0.65em;
    background-color: #6c757d;
  }
  
  /* Mejor espaciado */
  .table-responsive {
    margin-bottom: 1.5rem;
  }
</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <!-- Cabecera con título y acción -->
      <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
        <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
          <div class="me-3 p-2 rounded-circle bg-primary bg-opacity-10">
            <i class="bi bi-palette text-primary fs-3"></i>
          </div>
          <div>
            <h1 class="h4 fw-bold mb-0">Inventario de Modelos y Colores</h1>
            <p class="text-muted small mb-0">Disponibilidad actual en stock</p>
          </div>
        </div>

        <!-- Botón de acción -->
        <div class="d-flex align-items-center">
          <button onclick="location.reload()" 
                  class="btn btn-outline-primary d-flex align-items-center shadow-sm py-2 px-3 rounded">
            <i class="bi bi-arrow-clockwise me-2"></i>
            <span>Actualizar Listado</span>
          </button>
        </div>
      </header>

      <!-- Tarjeta principal -->
      <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
          <h2 class="h6 mb-0 text-secondary">
            <i class="bi bi-list-check me-2"></i>Resumen de Disponibilidad
          </h2>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="fw-semibold">Modelo</th>
                  <th class="fw-semibold">Color</th>
                  <th class="fw-semibold text-center">Disponibles</th>
                </tr>
              </thead>
              <tbody>
                @forelse($resultados as $item)
                  <tr>
                    <td class="fw-semibold">{{ $item->nombre_modelo }}</td>
                    <td>
                      <span class="d-inline-flex align-items-center">
                        <span class="color-preview me-2" 
                              style="display: inline-block; width: 16px; height: 16px; background-color: {{ $item->hex_color ?? '#cccccc' }}; border-radius: 2px;"></span>
                        {{ $item->nombre_color }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge rounded-pill badge-total">
                        {{ $item->total_disponibles }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <div class="d-flex flex-column align-items-center text-muted">
                        <i class="bi bi-exclamation-circle display-6 mb-2"></i>
                        <span>No hay registros disponibles</span>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Sección de resumen -->
        @if(count($resultados) > 0)
        <div class="card-footer bg-white py-3 border-top">
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Mostrando {{ count($resultados) }} registros</small>
            <div class="d-flex align-items-center">
              <i class="bi bi-info-circle text-primary me-2"></i>
              <small class="text-muted">Total general: {{ $resultados->sum('total_disponibles') }} unidades</small>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>



@endsection