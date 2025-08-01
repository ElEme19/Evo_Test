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

  /* Transiciones suaves */
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

  /* Imágenes de sucursal */
  .fachada-img {
    width: 40px !important;
    height: 40px !important;
    object-fit: cover !important;
    transition: transform .2s ease;
  }
  

</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <!-- HEADER PRINCIPAL -->
      <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
        
        <!-- TÍTULO E ICONO -->
        <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
          <div class="me-3 p-2 rounded-circle bg-success bg-opacity-10">
            <i class="bi bi-building text-success fs-3"></i>
          </div>
          <div>
            <h1 class="h4 fw-bold mb-0">Sucursales</h1>
            <p class="text-muted small mb-0">Gestión de sucursales registradas</p>
          </div>
        </div>

        <!-- BÚSQUEDA + ACCIÓN -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 w-100 w-md-auto" style="max-width: 380px;">
          
          <!-- Formulario de búsqueda compacto -->
          <form action="{{ route('sucursal.buscar') }}" method="GET" class="flex-grow-1 position-relative">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0 rounded-start">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" id="inputBuscar" name="q" value="{{ request('q') }}" 
                     class="form-control border-start-0 rounded-end" 
                     placeholder="Buscar sucursal..." style="min-width: 180px;">
              <button type="submit" class="btn btn-sm position-absolute end-0 top-0 h-100 rounded-end px-3 d-none d-sm-block" 
                      style="background-color: transparent; color: #6c757d;">
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </form>
          
          <!-- Botón NUEVA SUCURSAL -->
          @if(auth()->user()->rol == 0)
            <a href="{{ route('Sucursal.crear') }}"
               class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center shadow-sm py-2 px-3 rounded">
              <i class="bi bi-plus-circle-fill me-2"></i>
              <span>Nueva sucursal</span>
            </a>
          @endif
        </div>
      </header>

      <!-- ALERTAS -->
      @if(isset($busqueda) && $busqueda)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-search me-2"></i>Mostrando resultados para: <strong>{{ $busqueda }}</strong>
          <a href="{{ route('sucursal.ver') }}" class="btn btn-sm btn-link">Limpiar</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif

      @if (session('success'))
        <div class="text-center">
          <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <small class="fw-semibold">{{ session('success') }}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      @endif

      @if (session('error'))
        <div class="text-center">
          <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <small class="fw-semibold">{{ session('error') }}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      @endif

      <!-- Tarjeta contenedora de la tabla -->
      <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
          <h2 class="h6 mb-0 text-secondary">
            <i class="bi bi-list-check me-2"></i>Sucursales Registradas
          </h2>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaSucursales">
              <thead class="bg-light">
                <tr>
                  <th class="fw-semibold text-center">ID</th>
                  <th class="fw-semibold">Cliente</th>
                  <th class="fw-semibold">Nombre</th>
                  <th class="fw-semibold">Ubicación</th>
                  <th class="fw-semibold text-center">Fachada</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse($sucursales as $sucursal)
                  <tr>
                    <td class="text-center fw-semibold">{{ $sucursal->id_sucursal }}</td>
                    <td>{{ $sucursal->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td>{{ $sucursal->nombre_sucursal }}</td>
                    <td>{{ $sucursal->localizacion }}</td>
                    <td class="text-center">
                     @if($sucursal->foto_fachada)
                      <img src="{{ route('sucursal.imagen', ['path' => $sucursal->foto_fachada]) }}"
                          alt="Fachada"
                          class="fachada-img rounded shadow-sm"
                          style="cursor:pointer"
                          data-bs-toggle="modal"
                          data-bs-target="#modalImagen"
                          data-src="{{ route('sucursal.imagen', ['path' => $sucursal->foto_fachada]) }}">
                    @else
                      <div class="fachada-img bg-light rounded d-flex align-items-center justify-content-center" >
                        <i class="bi bi-image text-muted"></i>
                      </div>



                        <span class="badge bg-danger">Sin imagen</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-2">
                        <a href="#" 
                           class="btn btn-outline-primary btn-sm rounded-pill px-3">
                          <i class="bi bi-pencil-square me-1"></i>Editar
                        </a>
                        
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <div class="d-flex flex-column align-items-center text-muted">
                        <i class="bi bi-building display-5 mb-3"></i>
                        <span>No hay sucursales registradas</span>
                        @if (auth()->user()->rol == 0)
                          <a href="{{ route('Sucursal.crear') }}" class="btn btn-link mt-2">
                            Crear primera sucursal
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

        @if($sucursales->hasPages())
          <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
              {{ $sucursales->links('pagination::bootstrap-4') }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>


<!-- Modal para ver imagen en grande -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalImagenLabel">Vista ampliada</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagenAmpliada" src="" class="img-fluid rounded shadow" alt="Imagen ampliada">
      </div>
    </div>
  </div>
</div>




<script>
  const modalImagen = document.getElementById('modalImagen');
  modalImagen.addEventListener('show.bs.modal', function (event) {
    const img = event.relatedTarget;
    const src = img.getAttribute('data-src');
    const modalImg = modalImagen.querySelector('#imagenAmpliada');
    modalImg.src = src;
  });
</script>

<!-- FILTRADO DINÁMICO -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
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