<div class="modal fade" id="modalActualizar{{ $precio->id_precio }}" tabindex="-1" aria-labelledby="modalLabel{{ $precio->id_precio }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalLabel{{ $precio->id_precio }}">
                    Actualizar Precio: #{{ $precio->id_precio }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form action="{{ route('Precio.update', $precio->id_precio) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
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
                    </div>

                    <div class="mb-3">
                        <label for="id_modelo_{{ $precio->id_precio }}" class="form-label">Modelo de Bicicleta</label>
                        <select name="id_modelo" id="id_modelo_{{ $precio->id_precio }}" class="form-select" required>
                            <option value="" disabled>-- Seleccionar modelo --</option>
                            @foreach($modelos as $modelo)
                                <option value="{{ $modelo->id_modelo }}"
                                    {{ $precio->id_modelo == $modelo->id_modelo ? 'selected' : '' }}>
                                    {{ $modelo->nombre_modelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio_{{ $precio->id_precio }}" class="form-label">Precio ($ MXN)</label>
                        <input type="number" name="precio" id="precio_{{ $precio->id_precio }}" class="form-control" value="{{ $precio->precio }}" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
