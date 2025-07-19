@extends('layout.app')

@section('conten-wrapper')

<style>
  /* Ajustes de tabla */
  .table td, .table th { padding: .3rem .5rem !important; }
  .card-header, .card-footer { padding: .75rem 1rem !important; }
  .table-hover tbody tr { line-height: 1.2 !important; }
  .profile-img { width: 40px; height: 40px; object-fit: cover; }
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

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
              <th class="fw-semibold">Codigo de Pieza</th>
              <th class="fw-semibold">Modelo</th>
              <th class="fw-semibold">Nombre</th>
              <th class="fw-semibold">Color</th>
              <th class="fw-semibold">Descripción</th>
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
                          data-src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}">

                  @else
                    <div class="profile-img rounded bg-light d-flex align-items-center justify-content-center">
                      <i class="bi bi-image text-muted"></i>
                    </div>
                  @endif
                </td>
                <td>{{ $pieza->id_pieza }}</td>
                <td>{{ $pieza->modelo->nombre_modelo ?? $pieza->id_modelo }}</td>
                <td>{{ $pieza->nombre_pieza }}</td>
                <td>{{ $pieza->color }}</td>
                <td>{{ Str::limit($pieza->descripcion_general, 40) }}</td>
                <td class="text-center">
                  <a href="{{ route('pieza.editar', $pieza) }}" class="btn btn-outline-primary btn-sm me-1">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                 
                    
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
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

@endsection
