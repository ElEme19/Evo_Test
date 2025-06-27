<div class="modal fade" id="modalCrearModelo" tabindex="-1" aria-labelledby="modalCrearModeloLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <form method="POST" action="{{ route('Modelo.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Encabezado del modal -->
        <div class="modal-header border-0">
          <h5 class="modal-title fw-normal text-dark" id="modalCrearModeloLabel">
            Nuevo Modelo
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        
        <!-- Cuerpo del modal -->
        <div class="modal-body px-4 pb-4 pt-0">
          <div class="mb-3">
            <label for="id_modelo" class="form-label text-secondary small">ID Modelo</label>
            <input type="text" name="id_modelo" class="form-control rounded-0 border-top-0 border-start-0 border-end-0" 
                   id="id_modelo" required maxlength="64">
          </div>
          
          <div class="mb-3">
            <label for="nombre_modelo" class="form-label text-secondary small">Nombre del Modelo</label>
            <input type="text" name="nombre_modelo" class="form-control rounded-0 border-top-0 border-start-0 border-end-0" 
                   id="nombre_modelo" required maxlength="15">
          </div>
          
          <div class="mb-4">
            <label for="foto_modelo" class="form-label text-secondary small">Foto del Modelo</label>
            <input type="file" name="foto_modelo" class="form-control rounded-0 border-top-0 border-start-0 border-end-0" 
                   id="foto_modelo" accept="image/*">
            <div class="form-text">Formatos: JPG, PNG (Max. 2MB)</div>
          </div>
        </div>
        
        <!-- Pie del modal -->
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-sm btn-link text-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-sm btn-dark rounded-0">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>