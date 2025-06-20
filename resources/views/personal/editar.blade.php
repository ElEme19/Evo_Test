<!-- Modal Editar Área -->
<div class="modal fade" id="modalEditarArea" tabindex="-1" aria-labelledby="modalEditarAreaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarArea" method="POST" class="modal-content needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarAreaLabel">Editar Área</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_area" id="edit_id_area">
                <div class="mb-3">
                    <label for="edit_nombre_area" class="form-label">Nombre del Área</label>
                    <input type="text" name="nombre_area" id="edit_nombre_area" class="form-control" required maxlength="100" autocomplete="off">
                    <div class="invalid-feedback">Por favor ingresa el nombre del área.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
