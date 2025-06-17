
<div class="modal fade" id="editModal{{ $pieza->id_piezas }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $pieza->id_piezas }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel{{ $pieza->id_piezas }}">Actualiza</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm{{ $pieza->id_piezas }}" method="POST" action="{{ route('piezas.update', $pieza) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="nombre_pieza">Nombre:</label>
            <input type="text" id="nombre_pieza" name="nombre_pieza" value="{{ $pieza->nombre_pieza }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="descripcion_pieza">Descripción Pieza:</label>
            <input type="text" id="descripcion_pieza" name="descripcion_pieza" value="{{ $pieza->descripcion_pieza }}" class="form-control" required>
          </div>

          
          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal{{ $pieza->id_piezas }}" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="confirmModal{{ $pieza->id_piezas }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $pieza->id_piezas }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel{{ $pieza->id_piezas }}">¿Seguro que estás seguro?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la pieza. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
     
        <button class="btn btn-danger" data-bs-target="#editModal{{ $pieza->id_piezas }}" data-bs-toggle="modal">
          Volver
        </button>
       
        <button class="btn btn-success" onclick="document.getElementById('updateForm{{ $pieza->id_piezas }}').submit();">
          Sí, actualizar
        </button>
      </div>
    </div>
  </div>
</div>
