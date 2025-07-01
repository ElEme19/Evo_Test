@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Espaciado compacto en tablas */
  .table td, .table th {
    padding: .3rem .5rem !important;
  }

  /* Encabezados de cards */
  .card-header, .card-footer {
    padding: .75rem 1rem !important;
  }

  /* Altura de línea en filas hover */
  .table-hover tbody tr {
    line-height: 1.2 !important;
  }

  /* Transiciones suaves en inputs y botones */
  .input-group-text,
  .btn-success {
    transition: all .2s ease;
  }
  .form-control:focus + .input-group-text {
    border-color: #198754;
    color: #198754;
  }
  .btn-success:hover {
    background-color: #157347;
    transform: translateY(-2px);
  }
</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <!-- HEADER PRINCIPAL -->
      <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
        
        <!-- TÍTULO E ICONO -->
       <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">  <!-- Añadido margen derecho -->
  <div class="me-3 p-2 rounded-circle bg-success bg-opacity-10">
    <i class="bi bi-tag-fill text-success fs-3"></i>
  </div>
  <div>
    <h1 class="h4 fw-bold mb-0">Precios</h1>
    <p class="text-muted small mb-0">Gestión de listas de precios</p>  <!-- Texto descriptivo opcional -->
  </div>
</div>

<!-- BÚSQUEDA + ACCIÓN -->
<div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 w-100 w-md-auto" style="max-width: 380px;">  <!-- Ancho máximo añadido -->
  
  <!-- Formulario de búsqueda compacto -->
  <form action="{{ route('Precio.buscar') }}" method="GET" class="flex-grow-1 position-relative">
    <div class="input-group input-group-sm">
      <span class="input-group-text bg-white border-end-0 rounded-start">
        <i class="bi bi-search text-muted"></i>
      </span>
      <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 rounded-end" placeholder="Buscar..." style="min-width: 180px;">
      <button type="submit" class="btn btn-sm position-absolute end-0 top-0 h-100 rounded-end px-3 d-none d-sm-block" style="background-color: transparent; color: #6c757d;">
        <i class="bi bi-arrow-right"></i>  <!-- Icono más minimalista -->
      </button>
    </div>
  </form>
</div>

          <!-- Botón NUEVO PRECIO -->
          @if(auth()->user()->rol == 0)
            <a href="{{ route('Precio.create') }}"
               class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow-sm py-2 px-3 rounded">
              <i class="bi bi-plus-circle-fill me-2"></i>
              <span>Nuevo precio</span>
            </a>
          @endif

        </div>
      </header>

            @if(isset($busqueda) && $busqueda)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-search me-2"></i>Mostrando resultados para: <strong>{{ $busqueda }}</strong>
                    <a href="{{ route('Precio.index') }}" class="btn btn-sm btn-link">Limpiar</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <!-- Alertas de sesión -->
            @if (session('success'))
                <div class="text-center">
                    <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <small class="fw-semibold">{{ session('success') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="text-center">
                    <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <small class="fw-semibold">{{ session('error') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Tarjeta contenedora de la tabla -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h2 class="h6 mb-0 text-secondary">
                        <i class="bi bi-list-check me-2"></i>Precios Registrados
                    </h2>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaPrecios">
                            <thead class="bg-light">
                                <tr>
                                    
                                    <th class="fw-semibold text-center">Modelo</th>
                                    <th class="fw-semibold text-center">Voltaje</th>
                                    <th class="fw-semibold text-center">Membresía</th>
                                    <th class="fw-semibold text-center">Precio</th>
                                    <th class="fw-semibold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($precios as $p)
                                    <tr>
                                        
                                        <td class="text-center fw-semibold">{{ $p->modelo->nombre_modelo ?? '#ERROR' }}</td>
                                        <td class="text-center fw-semibold">{{ $p->voltaje->tipo_voltaje ?? '#ERROR' }}</td>
                                        <td class="text-center fw-semibold">{{ $p->membresia->descripcion_general ?? 'Sin tipo' }}</td>
                                        <td class="text-center text-success fw-bold">${{ number_format($p->precio, 2) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalActualizar{{ $p->id_precio }}">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </button>
                                            @include('Precio.update', ['precio' => $p])
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center text-muted">
                                                <i class="bi bi-tag display-5 mb-3"></i>
                                                <span>No hay precios registrados</span>
                                                @if (auth()->user()->rol == 0)
                                                    <a href="{{ route('Precio.create') }}" class="btn btn-link mt-2">
                                                        Crear primer precio
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($precios->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-center">
                            {{ $precios->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Función para normalizar texto (búsqueda sin acentos y case insensitive)
        const normalizarTexto = (texto) => {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/\s+/g, " ")
                .trim();
        };

        // Si hay parámetro de búsqueda en la URL, lo colocamos en el input
        const urlParams = new URLSearchParams(window.location.search);
        const queryParam = urlParams.get('q');
        if(queryParam) {
            document.querySelector('input[name="q"]').value = queryParam;
        }
    });
</script>
@endsection