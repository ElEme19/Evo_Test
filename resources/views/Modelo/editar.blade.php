<div class="modal fade" id="modalActualizar{{ $modelo->id_modelo }}" tabindex="-1" aria-labelledby="modalActualizarLabel{{ $modelo->id_modelo }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('Modelo.update', $modelo->id_modelo) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="modalActualizarLabel{{ $modelo->id_modelo }}">Editar Modelo: {{ $modelo->id_modelo }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
            <input type="text" name="nombre_modelo" class="form-control" value="{{ $modelo->nombre_modelo }}" required maxlength="15">
          </div>
          
          <div class="mb-3">
            <label for="foto_modelo" class="form-label">Actualizar Foto (opcional)</label>
            <input type="file" name="foto_modelo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
