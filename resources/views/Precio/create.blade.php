@extends('layout.app')

@section('conten')

<div class="text-center my-4">
    <h3>
        Precio
        <span class="badge rounded-pill text-bg-success">Nuevo</span>
    </h3>
</div>

@if (session('success'))
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <!-- ícono SVG -->
            <small class="fw-semibold">{{ session('success') }}</small>
        </div>
    </div>
@endif
@if (session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong>{{ session('error') }}</strong>
        </div>
    </div>
@endif

<form class="row g-3 was-validated" method="POST" action="{{ route('Precio.store') }}">
    @csrf

    <div class="col-md-4">
        <label for="id_precio" class="form-label">ID Precio (Generado)</label>
        <input type="text" class="form-control" id="id_precio" name="id_precio"
               value="{{ $nextId }}" readonly>
    </div>

    <div class="col-md-4">
        <label for="id_membresia" class="form-label">Seleccionar Membresía</label>
        <select name="id_membresia" id="id_membresia" class="form-select" required>
            <option value="" disabled selected>Seleccione una membresía</option>
            @foreach($membresias as $m)
                <option value="{{ $m->id_membresia }}"
                    {{ old('id_membresia') == $m->id_membresia ? 'selected' : '' }}>
                    {{ $m->descripcion_general ?? $m->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_modelo" class="form-label">Seleccionar Modelo</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required>
            <option value="" selected>Seleccione un modelo</option>
            @foreach($modelos as $modelo)
                <option value="{{ $modelo->id_modelo }}"
                    {{ old('id_modelo') == $modelo->id_modelo ? 'selected' : '' }}>
                    {{ $modelo->nombre_modelo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="precio" class="form-label">Precio ($)</label>
        <input type="number" name="precio" id="precio"
               value="{{ old('precio') }}" step="0.01" min="0"
               class="form-control is-valid" required>
        <div class="invalid-feedback">Ingrese un precio válido.</div>
    </div>

    <div class="col-md-4">
        <label for="id_voltaje" class="form-label">Voltaje (disponible según modelo)</label>
        <select name="id_voltaje" id="id_voltaje" class="form-select" required disabled>
            <option value="" selected disabled>Seleccione un modelo primero</option>
        </select>
        <div class="invalid-feedback">Debe seleccionar un voltaje válido.</div>
    </div>

    <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">Guardar Precio</button>
        </div>
        <div class="col text-end">
            <a href="{{ route('Precio.index') }}" class="btn btn-outline-success">Ver Precios</a>
        </div>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(function() {
    // Cache de selectores
    const $modelo  = $('#id_modelo');
    const $voltaje = $('#id_voltaje');

    // Opción por defecto
    const OPTION_DEF = '<option value="" disabled selected>Seleccione un voltaje</option>';

    /**
     * Carga los voltajes disponibles para el modelo dado.
     * @param {string} idModelo 
     */
    function cargarVoltajes(idModelo) {
      // Construir URL dinámicamente
      const url = `{{ route("voltaje.porModelo", ":id") }}`.replace(':id', idModelo);

      // Mostrar estado de carga y deshabilitar select
      $voltaje
        .prop('disabled', true)
        .html('<option disabled selected>Cargando…</option>');

      // Llamada AJAX
      $.getJSON(url)
        .done(function(voltajes) {
          let html = OPTION_DEF;

          if (!Array.isArray(voltajes) || voltajes.length === 0) {
            html = '<option value="" disabled>No hay voltajes disponibles</option>';
          } else {
            // Poblar opciones
            voltajes.forEach(v => {
              html += `<option value="${v.id_voltaje}">${v.tipo_voltaje}</option>`;
            });
          }

          // Inyectar y habilitar
          $voltaje.html(html).prop('disabled', false);
        })
        .fail(function() {
          $voltaje.html('<option value="" disabled>Error al cargar voltajes</option>');
        });
    }

    // Al cambiar el modelo, disparar carga de voltajes
    $modelo.on('change', function() {
      const id = $(this).val();
      if (id) {
        cargarVoltajes(id);
      } else {
        // Si vuelven a la opción vacía
        $voltaje.html('<option value="" disabled selected>Seleccione un modelo primero</option>');
      }
    });
  });
</script>




@endsection