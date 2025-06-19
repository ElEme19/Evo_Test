<div class="modal fade" id="modalCrearModelo" tabindex="-1" aria-labelledby="modalCrearModeloLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('Modelo.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearModeloLabel">Crear Nuevo Modelo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="id_modelo" class="form-label">ID Modelo</label>
            <input type="text" name="id_modelo" class="form-control" required maxlength="64">
          </div>
          <div class="mb-3">
            <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
            <input type="text" name="nombre_modelo" class="form-control" required maxlength="15">
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="2" required maxlength="64"></textarea>
          </div>
          <div class="mb-3">
            <label for="foto_modelo" class="form-label">Foto del Modelo</label>
            <input type="file" name="foto_modelo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
