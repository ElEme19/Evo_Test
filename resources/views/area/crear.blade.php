<!-- Modal Crear Área -->
<div class="modal fade" id="modalCrearArea" tabindex="-1" aria-labelledby="modalCrearAreaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('area.store') }}" method="POST" class="modal-content needs-validation" novalidate>
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearAreaLabel">Crear Nueva Área</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre_area" class="form-label">Nombre del Área</label>
                    <input type="text" name="nombre_area" id="nombre_area" class="form-control" required maxlength="100" autocomplete="off">
                    <div class="invalid-feedback">Por favor ingresa el nombre del área.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Área</button>
            </div>
        </form>
    </div>
</div>