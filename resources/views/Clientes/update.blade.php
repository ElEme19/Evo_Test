<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar{{ $c->id_cliente }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $c->id_cliente }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel{{ $c->id_cliente }}">Actualizar Cliente</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm{{ $c->id_cliente }}" method="POST" action="{{ route('Clientes.update', $c->id_cliente) }}">
          @csrf
          @method('PUT')

          <input type="hidden" name="id_cliente" value="{{ $c->id_cliente }}">

          <div class="mb-3">
            <label>ID Cliente:</label>
            <p class="fw-bold mb-0">{{ $c->id_cliente }}</p>
          </div>

         <div class="mb-3">
  <label for="id_membresia{{ $c->id_cliente }}">Seleccionar Membresía:</label>
  <select name="id_membresia" id="id_membresia{{ $c->id_cliente }}" class="form-select" required>
    <option value="" disabled>Seleccione una membresía</option>
    @foreach ($membresias as $m)
      <option value="{{ $m->id_membresia }}" {{ $c->id_membresia == $m->id_membresia ? 'selected' : '' }}>
        {{ $m->descripcion_general }}
      </option>
    @endforeach
  </select>
</div>



          <div class="mb-3">
            <label for="nombre{{ $c->id_cliente }}">Nombre:</label>
            <input type="text" id="nombre{{ $c->id_cliente }}" name="nombre" value="{{ $c->nombre }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="apellido{{ $c->id_cliente }}">Apellido:</label>
            <input type="text" id="apellido{{ $c->id_cliente }}" name="apellido" value="{{ $c->apellido }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="telefono{{ $c->id_cliente }}">Teléfono:</label>
            <input type="text" id="telefono{{ $c->id_cliente }}" name="telefono" value="{{ $c->telefono }}" class="form-control" required>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal{{ $c->id_cliente }}" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal{{ $c->id_cliente }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel{{ $c->id_cliente }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel{{ $c->id_cliente }}">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la información del cliente. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar{{ $c->id_cliente }}" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm{{ $c->id_cliente }}').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
