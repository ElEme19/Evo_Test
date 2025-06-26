@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
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
          <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <small class="fw-semibold">{{ session('success') }}</small>
          </div>
        </div>
      @endif

      @if (session('error'))
        <div class="text-center mb-3">
          <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm">
            <i class="bi bi-x-circle-fill me-2"></i>
            <small class="fw-semibold">{{ session('error') }}</small>
          </div>
        </div>
      @endif

     

      <!-- Tabla -->
      <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaModelos">
              <thead class="table-light">
                <tr>
                  <th class="text-center">ID Modelo</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Imagen</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($modelos as $modelo)
                  <tr>
                    <td class="text-center fw-semibold">{{ $modelo->id_modelo }}</td>
                    <td class="text-center">{{ $modelo->nombre_modelo }}</td>
                    <td class="text-center">
                    @if ($modelo->foto_modelo)
                        <a href="#" class="d-inline-block" data-bs-toggle="modal" data-bs-target="#imagenModal" 
                        onclick="mostrarImagen('{{ base64_encode($modelo->foto_modelo) }}')">
                        <img src="data:image/jpeg;base64,{{ base64_encode($modelo->foto_modelo) }}" 
                            width="60" height="60"
                            class="rounded shadow-sm object-fit-cover cursor-pointer"
                            alt=" {{ $modelo->nombre_modelo }}"
                            style="max-height: 60px; object-fit: contain;">
                        </a>
                    @else
                        <span class="text-muted">Sin imagen</span>
                    @endif
                    </td>
                    <td class="text-center">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar{{ $modelo->id_modelo }}"
                      >
                        <i class="bi bi-pencil-square"></i>
                        <span class="d-none d-md-inline">Editar</span>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                      <i class="bi bi-exclamation-circle mb-2" style="font-size: 1.5rem;"></i>
                      <p class="mb-0">No hay modelos registrados</p>
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



<!-- Modal Actualizar Modelo -->
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
              <label class="form-label">Imagen actual</label>
              @if ($modelo->foto_modelo)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $modelo->foto_modelo) }}" width="100" class="rounded mb-2" alt="Imagen actual">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="eliminar_foto" id="delFoto{{ $modelo->id_modelo }}">
                    <label class="form-check-label" for="delFoto{{ $modelo->id_modelo }}">Eliminar imagen</label>
                  </div>
                </div>
              @endif
              <input type="file" name="foto_modelo" class="form-control" accept="image/*">
              <small class="text-muted">Dejar vacío para mantener la imagen</small>
            </div>
            <button type="button" class="btn btn-success w-100" data-bs-target="#confirmModal{{ $modelo->id_modelo }}" data-bs-toggle="modal">
              Actualizar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Confirmación -->
  <div class="modal fade" id="confirmModal{{ $modelo->id_modelo }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmLabel{{ $modelo->id_modelo }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLabel{{ $modelo->id_modelo }}">¿Confirmar actualización?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Esta acción actualizará los datos del modelo <strong>{{ $modelo->nombre_modelo }}</strong>. ¿Deseas continuar?
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" data-bs-target="#modalActualizar{{ $modelo->id_modelo }}" data-bs-toggle="modal">Volver</button>
          <button class="btn btn-success" onclick="document.getElementById('updateForm{{ $modelo->id_modelo }}').submit()">Sí, actualizar</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

<!-- Script JS de búsqueda -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputBuscar');
    const tabla = document.querySelector('#tablaModelos tbody');
    const sinResultados = document.getElementById('noResultados');

    const normalizarTexto = texto =>
      texto.toLowerCase()
           .normalize('NFD')
           .replace(/[\u0300-\u036f]/g, '')
           .trim();

    input.addEventListener('input', () => {
      const filtro = normalizarTexto(input.value);
      const filas = tabla.querySelectorAll('tr');
      let count = 0;

      filas.forEach(fila => {
        const id   = normalizarTexto(fila.cells[0]?.textContent || '');
        const name = normalizarTexto(fila.cells[1]?.textContent || '');
        const mostrar = id.includes(filtro) || name.includes(filtro);
        fila.classList.toggle('d-none', !mostrar);
        if (mostrar) count++;
      });

      sinResultados.classList.toggle('d-none', count > 0);
    });
  });
</script>
@endsection
