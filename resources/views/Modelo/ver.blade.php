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
  .btn-success:hover {
    background-color: #157347;
    transform: translateY(-2px);
  }

  /* Imagen de modelo */
  .modelo-img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    cursor: pointer;
    transition: transform .2s ease;
  }
</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

      <!-- Título con badge -->
      <div class="text-center my-4">
        <h3 class="d-flex align-items-center justify-content-center">
          <span class="me-2">Gestión de Modelos</span>
          <span class="badge rounded-pill text-bg-success">Administrar</span>
        </h3>
      </div>

     

      <!-- Alertas -->
      @if (session('success'))
        <div class="text-center mb-3">
          <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <small class="fw-semibold">{{ session('success') }}</small>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        </div>
      @endif

      @if (session('error'))
        <div class="text-center mb-3">
          <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>
            <small class="fw-semibold">{{ session('error') }}</small>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        </div>
      @endif



       <!-- Botón Crear -->
      @if (auth()->user()->rol == 0)
        <div class="d-flex justify-content-center mb-3">
          <button type="button" class="btn btn-outline-success shadow-sm py-2 px-3 rounded mx-auto" ...>
            <i class="bi bi-plus-circle-fill me-2"></i>
            Crear Nuevo Modelo
          </button>
        </div>

        @include('Modelo.crear')
      @endif

      <!-- Tabla -->
      <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
          <h2 class="h6 mb-0 text-secondary">
            <i class="bi bi-list-check me-2"></i>Modelos Registrados
          </h2>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaModelos">
              <thead class="bg-light">
                <tr>
                  <th class="text-center fw-semibold">ID Modelo</th>
                  <th class="text-center fw-semibold">Nombre</th>
                  <th class="text-center fw-semibold">Imagen</th>
                  <th class="text-center fw-semibold">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($modelos as $modelo)
                  <tr>
                    <td class="text-center fw-semibold">{{ $modelo->id_modelo }}</td>
                    <td class="text-center">{{ $modelo->nombre_modelo }}</td>
                    <td class="text-center">
                     @if($modelo->foto_modelo)
                        <img src="{{ route('Modelo.imagen', ['path' => $modelo->foto_modelo]) }}"
                            alt="{{ $modelo->nombre_modelo }}"
                            class="modelo-img rounded shadow-sm"
                            style="cursor:pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#modalImagen"
                            data-src="{{ route('Modelo.imagen', ['path' => $modelo->foto_modelo]) }}">
                    @else

                        <span class="text-muted">Sin imagen</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalActualizar{{ $modelo->id_modelo }}">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span class="d-none d-md-inline">Editar</span>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                      <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-exclamation-circle display-5 mb-2"></i>
                        <p class="mb-0">No hay modelos registrados</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
            <!-- Mensaje “no resultados” -->
            <div id="noResultados" class="text-center text-muted d-none py-3">
              <i class="bi bi-search mb-2" style="font-size: 1.5rem;"></i>
              <p class="mb-0">No se encontraron coincidencias</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginación -->
      @if($modelos->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div class="small text-muted">
            Mostrando {{ $modelos->firstItem() }}–{{ $modelos->lastItem() }} de {{ $modelos->total() }} registros
          </div>
          <nav>
            {{ $modelos->withQueryString()->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      @endif

    </div>
  </div>
</div>

<!-- Modal ver imagen -->
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

<!-- Modales Actualizar y Confirmar -->
@foreach($modelos as $modelo)
  <div class="modal fade" id="modalActualizar{{ $modelo->id_modelo }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel{{ $modelo->id_modelo }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="editLabel{{ $modelo->id_modelo }}">Editar Modelo: {{ $modelo->nombre_modelo }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm{{ $modelo->id_modelo }}" method="POST" action="{{ route('Modelo.update', $modelo->id_modelo) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">ID Modelo</label>
              <p class="form-control-plaintext">{{ $modelo->id_modelo }}</p>
            </div>

            <div class="mb-3">
              <label class="form-label">Nombre del Modelo</label>
              <input type="text" name="nombre_modelo" class="form-control" value="{{ $modelo->nombre_modelo }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Foto Actual</label><br>
              @if($modelo->foto_modelo)
                <img src="{{ route('Modelo.imagen', ['path' => $modelo->foto_modelo]) }}"
                     alt="{{ $modelo->nombre_modelo }}"
                     class="modelo-img rounded shadow-sm"
                     style="cursor:pointer; max-width: 100px;"
                     data-bs-toggle="modal"
                     data-bs-target="#modalImagen"
                     data-src="{{ route('Modelo.imagen', ['path' => $modelo->foto_modelo]) }}">
              @else
                <p class="text-muted">Sin imagen</p>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label">Nueva Imagen (opcional)</label>
              <input type="file" name="foto_modelo" class="form-control" accept="image/*">
              <small class="text-muted">Dejar vacío para mantener la imagen actual.</small>
            </div>

            <!-- Botón para confirmar -->
            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $modelo->id_modelo }}">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="confirmModal{{ $modelo->id_modelo }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmLabel{{ $modelo->id_modelo }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLabel{{ $modelo->id_modelo }}">¿Confirmar actualización?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          Esta acción actualizará los datos del modelo <strong>{{ $modelo->nombre_modelo }}</strong>. ¿Deseas continuar?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalActualizar{{ $modelo->id_modelo }}">Volver</button>
          <button type="button" class="btn btn-success" onclick="document.getElementById('updateForm{{ $modelo->id_modelo }}').submit()">Sí, actualizar</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

<script>
  // Script para mostrar imagen ampliada en modal
  const modalImagen = document.getElementById('modalImagen');
  modalImagen.addEventListener('show.bs.modal', function (event) {
    const img = event.relatedTarget;
    const src = img.getAttribute('data-src');
    const modalImg = modalImagen.querySelector('#imagenAmpliada');
    modalImg.src = src;
  });
</script>

@endsection
