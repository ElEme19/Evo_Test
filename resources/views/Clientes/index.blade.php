@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Estilos consistentes con la vista de precios */
  .table td, .table th {
    padding: .3rem .5rem !important;
  }
  
  .card-header, .card-footer {
    padding: .75rem 1rem !important;
  }
  
  .table-hover tbody tr {
    line-height: 1.2 !important;
  }
  
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
  
  /* Estilos específicos para imágenes de perfil */
  .profile-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
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
            <i class="bi bi-people-fill text-success fs-3"></i>
          </div>
          <div>
            <h1 class="h4 fw-bold mb-0">Clientes</h1>
            <p class="text-muted small mb-0">Gestión de clientes registrados</p>
          </div>
        </div>

        <!-- BÚSQUEDA + ACCIÓN -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 w-100 w-md-auto" style="max-width: 380px;"> 
          
          <!-- Formulario de búsqueda compacto -->
          <form action="{{ route('Clientes.buscar') }}" method="GET" class="flex-grow-1 position-relative">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0 rounded-start">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 rounded-end" placeholder="Buscar cliente..." style="min-width: 180px;">
              <button type="submit" class="btn btn-sm position-absolute end-0 top-0 h-100 rounded-end px-3 d-none d-sm-block" style="background-color: transparent; color: #6c757d;">
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </form>

          <!-- Botón NUEVO CLIENTE -->
          @if(auth()->user()->rol == 0)
            <a href="{{ route('Clientes.create') }}"
               class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow-sm py-2 px-3 rounded">
              <i class="bi bi-plus-circle-fill me-2"></i>
              <span>Nuevo cliente</span>
            </a>
          @endif
        </div>
      </header>

      @if(isset($busqueda) && $busqueda)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-search me-2"></i>Mostrando resultados para: <strong>{{ $busqueda }}</strong>
          <a href="{{ route('Clientes.index') }}" class="btn btn-sm btn-link">Limpiar</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif

      <!-- Alertas de sesión -->
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
            <i class="bi bi-list-check me-2"></i>Clientes Registrados
          </h2>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaClientes">
              <thead class="bg-light">
                <tr>
                  <th class="fw-semibold text-center">Foto</th>
                  <th class="fw-semibold text-center">Nombre</th>
                  <th class="fw-semibold text-center">Apellido</th>
                  <th class="fw-semibold text-center">Teléfono</th>
                  <th class="fw-semibold text-center">Membresía</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($clientes as $c)
                  <tr>
                    <td class="text-center">
                      @if($c->foto_persona)
                        <img src="{{ asset('img/clientes/' . $c->foto_persona) }}" alt="Foto" class="profile-img rounded-circle">
                      @else
                        <div class="profile-img rounded-circle bg-light d-flex align-items-center justify-content-center">
                          <i class="bi bi-person text-muted"></i>
                        </div>
                      @endif
                    </td>
                    <td class="text-center fw-semibold">{{ $c->nombre }}</td>
                    <td class="text-center fw-semibold">{{ $c->apellido }}</td>
                    <td class="text-center fw-semibold">{{ $c->telefono }}</td>
                    <td class="text-center fw-semibold">{{ $c->membresia->descripcion_general ?? 'Sin membresía' }}</td>
                    <td class="text-center">
                      <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" 
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar{{ $c->id_cliente }}">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                      </button>
                      @include('Clientes.update', ['cliente' => $c])
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <div class="d-flex flex-column align-items-center text-muted">
                        <i class="bi bi-people display-5 mb-3"></i>
                        <span>No hay clientes registrados</span>
                        @if (auth()->user()->rol == 0)
                          <a href="{{ route('Clientes.create') }}" class="btn btn-link mt-2">
                            Registrar primer cliente
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

        @if($clientes->hasPages())
          <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
              {{ $clientes->links('pagination::bootstrap-4') }}
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