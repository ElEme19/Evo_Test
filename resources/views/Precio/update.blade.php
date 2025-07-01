<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar{{ $precio->id_precio }}" tabindex="-1" aria-labelledby="editModalLabel{{ $precio->id_precio }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel{{ $precio->id_precio }}">Actualizar Precio</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm{{ $precio->id_precio }}" method="POST" action="{{ route('Precio.update', $precio->id_precio) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <input type="hidden" name="id_precio" value="{{ $precio->id_precio }}">
            <label>ID Precio:</label>
            <p class="fw-bold mb-0">{{ $precio->id_precio }}</p>
          </div>
            
          <div class="mb-3">
    <label class="form-label">Modelo de Bicicleta</label>
    <p class="fw-bold mb-0">{{ $precio->modelo->nombre_modelo ?? 'Modelo no encontrado' }}</p>
    <input type="hidden" name="id_modelo" value="{{ $precio->id_modelo }}">
</div>


          <div class="mb-3">
            <label for="id_membresia_{{ $precio->id_precio }}" class="form-label">Tipo de Membresía</label>
            <select name="id_membresia" id="id_membresia{{ $precio->id_precio }}" class="form-select" required>
              <option value="" disabled>-- Seleccionar membresía --</option>
              @foreach($membresias as $membresia)
                <option value="{{ $membresia->id_membresia }}"
                  {{ $precio->id_membresia == $membresia->id_membresia ? 'selected' : '' }}>
                  {{ $membresia->descripcion_general }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback">Seleccione una membresía válida.</div>
          </div>


          <div class="mb-3">
            <label for="precio_{{ $precio->id_precio }}" class="form-label">Precio ($ MXN)</label>
            <input type="number" name="precio" id="precio_{{ $precio->id_precio }}" class="form-control" 
                   value="{{ $precio->precio }}" step="0.01" min="0" required>
            <div class="invalid-feedback">Ingrese un precio válido.</div>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal{{ $precio->id_precio }}" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal{{ $precio->id_precio }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
     aria-labelledby="confirmModalLabel{{ $precio->id_precio }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel{{ $precio->id_precio }}">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará el precio. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar{{ $precio->id_precio }}" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm{{ $precio->id_precio }}').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>