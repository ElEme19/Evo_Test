<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar{{ $m->id_membresia }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $m->id_membresia }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel{{ $m->id_membresia }}">Actualizar Membresía</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm{{ $m->id_membresia }}" method="POST" action="{{ route('Membresia.actualizar', $m->id_membresia) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <input type="hidden" name="id_membresia" value="{{ $m->id_membresia }}">
            <label>ID Membresía:</label>
            <p>{{ $m->id_membresia }}</p>
          </div>

          <div class="mb-3">
            <label for="descripcion_general">Descripción General:</label>
            <input type="text" id="descripcion_general" name="descripcion_general" value="{{ $m->descripcion_general }}" class="form-control" required>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal{{ $m->id_membresia }}" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal{{ $m->id_membresia }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel{{ $m->id_membresia }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel{{ $m->id_membresia }}">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la membresía. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar{{ $m->id_membresia }}" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm{{ $m->id_membresia }}').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
