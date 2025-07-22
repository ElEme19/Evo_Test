@extends('layout.app')

@section('conten-wrapper')

<style>
  /* Ajustes de tabla */
  .table td, .table th { padding: .3rem .5rem !important; }
  .card-header, .card-footer { padding: .75rem 1rem !important; }
  .table-hover tbody tr { line-height: 1.2 !important; }
  .profile-img { width: 40px; height: 40px; object-fit: cover; }
    .pagination .page-link {
        color: #198754; /* Verde Bootstrap */
    }
    .pagination .page-item.active .page-link {
        background-color: #198754;
        border-color: #198754;
        color: #fff;
    }


</style>



<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

  <!-- HEADER PRINCIPAL -->
  <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
      <div class="me-3 p-2 bg-success bg-opacity-10" aria-hidden="true">
        <i class="bi bi-box-seam text-success fs-3" role="img" aria-label="Icono de piezas"></i>
      </div>
      <div>
        <h1 class="h4 fw-bold mb-0">Piezas</h1>
        <p class="text-muted small mb-0">Gestión de refacciones</p>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
      <a href="{{ route('pieza.crear') }}" class="btn btn-success btn-sm d-flex align-items-center shadow-sm py-2 px-3 rounded">
        <i class="bi bi-plus-circle-fill me-2"></i><span>Nueva Pieza</span>
      </a>
    </div>
  </header>

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
 

  <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom">
      <h2 class="h6 mb-0 text-secondary">
        <i class="bi bi-list-check me-2"></i>Listado de Piezas
      </h2>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="fw-semibold text-center">Foto</th>
              <th class="fw-semibold text-center">Codigo de Pieza</th>
              <th class="fw-semibold text-center">Modelo</th>
              <th class="fw-semibold text-center">Nombre</th>
              <th class="fw-semibold text-center">Color</th>
              <th class="fw-semibold text-center">Descripción</th>
              <th class="fw-semibold text-center">Unidad</th>
              <th class="fw-semibold text-center">Cantidad</th>
              <th class="fw-semibold text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
  @forelse($piezas as $pieza)
    <tr>
      <td class="text-center">
        @if($pieza->foto_pieza)
          <img src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
               alt="Foto"
               class="profile-img rounded"
               style="cursor:pointer"
               data-bs-toggle="modal"
               data-bs-target="#modalImagen"
               data-src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
               data-nombre="{{ $pieza->nombre_pieza }}">
        @else
          <div class="profile-img rounded bg-light d-flex align-items-center justify-content-center">
            <i class="bi bi-image text-muted"></i>
          </div>
        @endif
      </td>
      <td class="text-center">{{ $pieza->id_pieza }}</td>
      <td class="text-center">{{ $pieza->modelo->nombre_modelo ?? $pieza->id_modelo }}</td>
      <td class="text-center">{{ $pieza->nombre_pieza }}</td>
      <td class="text-center">{{ $pieza->color }}</td>
      <td class="text-center">{{ Str::limit($pieza->descripcion_general, 40) }}</td>
      <td class="text-center">{{ $pieza->Unidad }}</td>
     <td class="text-center
        @if($pieza->cantidad > 100) text-success fw-bold
        @elseif($pieza->cantidad > 20) text-warning fw-bold
        @else text-danger fw-bold
        @endif
      ">
        @if($pieza->cantidad > 100)
          <i class="bi bi-check-circle-fill me-1"></i>
        @elseif($pieza->cantidad > 20)
          <i class="bi bi-exclamation-triangle-fill me-1"></i>
        @else
          <i class="bi bi-x-circle-fill me-1"></i>
        @endif
        {{ $pieza->cantidad }}
      </td>


      <td class="text-center">
        <!-- Botón para abrir modal -->
        <button type="button"
                class="btn btn-outline-primary btn-sm me-1"
                data-bs-toggle="modal"
                data-bs-target="#modalActualizar{{ $pieza->id_pieza }}">
          <i class="bi bi-pencil-square"></i>
        </button>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="9" class="text-center py-5 text-muted">
        <i class="bi bi-box-seam display-4 mb-3"></i><br>
        No hay piezas registradas.
      </td>
    </tr>
  @endforelse
</tbody>

        </table>
      </div>
    </div>
  </div>
  
<div id="paginationContainer">
  @if($piezas->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="text-muted small">
        Mostrando {{ $piezas->firstItem() }} a {{ $piezas->lastItem() }} de {{ $piezas->total() }} registros
      </div>
      <nav aria-label="Paginación">
        <ul class="pagination pagination-sm justify-content-center">

          {{-- Flecha atrás --}}
          @if($piezas->onFirstPage())
            <li class="page-item disabled">
              <span class="page-link text-success border-success bg-white">&laquo;</span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link text-success border-success bg-white" href="{{ $piezas->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>
          @endif

          {{-- Números de página --}}
          @foreach($piezas->getUrlRange(1, $piezas->lastPage()) as $page => $url)
            @if($page == $piezas->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link bg-success text-white border-success">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link text-success border-success bg-white" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach

          {{-- Flecha siguiente --}}
          @if($piezas->hasMorePages())
            <li class="page-item">
              <a class="page-link text-success border-success bg-white" href="{{ $piezas->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
          @else
            <li class="page-item disabled">
              <span class="page-link text-success border-success bg-white">&raquo;</span>
            </li>
          @endif

        </ul>
      </nav>
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
        <h3 class="modal-title text-center w-100" id="modalImagenLabel"></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagenAmpliada" src="" class="img-fluid rounded shadow" alt="Imagen ampliada">
      </div>
    </div>
  </div>
</div>

<!-- Modal para actualizar pieza -->
@foreach($piezas as $pieza)
  <div class="modal fade" id="modalActualizar{{ $pieza->id_pieza }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel{{ $pieza->id_pieza }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="editLabel{{ $pieza->id_pieza }}">Editar Pieza: {{ $pieza->nombre_pieza }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm{{ $pieza->id_pieza }}" method="POST" action="{{ route('pieza.update', $pieza->id_pieza) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">ID Pieza</label>
              <p class="form-control-plaintext">{{ $pieza->id_pieza }}</p>
            </div>

            <div class="mb-3">
              <label class="form-label">Nombre de la Pieza</label>
              <input type="text" name="nombre_pieza" class="form-control" value="{{ old('nombre_pieza', $pieza->nombre_pieza) }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion_general" class="form-control" rows="3" required>{{ old('descripcion_general', $pieza->descripcion_general) }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Color</label>
              <input type="text" name="color" class="form-control" value="{{ old('color', $pieza->color) }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Cantidad</label>
              <input type="number" name="cantidad" class="form-control" value="{{ old('cantidad', $pieza->cantidad) }}" min="0" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Foto Actual</label><br>
              @if($pieza->foto_pieza)
                <img src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
                     alt="{{ $pieza->nombre_pieza }}"
                     class="profile-img rounded shadow-sm"
                     style="cursor:pointer; max-width: 100px;"
                     data-bs-toggle="modal"
                     data-bs-target="#modalImagen"
                     data-src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
                     data-nombre="{{ $pieza->nombre_pieza }}">
              @else
                <p class="text-muted">Sin imagen</p>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label">Nueva Imagen (opcional)</label>
              <input type="file" name="foto_pieza" class="form-control" accept="image/*">
              <small class="text-muted">Dejar vacío para mantener la imagen actual.</small>
            </div>

            <!-- Botón para abrir modal de confirmación -->
            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $pieza->id_pieza }}">
              Actualizar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de confirmación -->
  <div class="modal fade" id="confirmModal{{ $pieza->id_pieza }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmLabel{{ $pieza->id_pieza }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLabel{{ $pieza->id_pieza }}">¿Confirmar actualización?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          Esta acción actualizará los datos de la pieza <strong>{{ $pieza->nombre_pieza }}</strong>. ¿Deseas continuar?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalActualizar{{ $pieza->id_pieza }}">
            Volver
          </button>
          <button type="button" class="btn btn-success" onclick="document.getElementById('updateForm{{ $pieza->id_pieza }}').submit()">
            Sí, actualizar
          </button>
        </div>
      </div>
    </div>
  </div>
@endforeach




<script>
  const modalImagen = document.getElementById('modalImagen');
  modalImagen.addEventListener('show.bs.modal', function (event) {
    const img = event.relatedTarget;
    const src = img.getAttribute('data-src');
    const nombre = img.getAttribute('data-nombre');

    const modalImg = modalImagen.querySelector('#imagenAmpliada');
    const modalTitle = modalImagen.querySelector('#modalImagenLabel');

    modalImg.src = src;
    modalTitle.textContent = nombre;
  });
</script>

@endsection
