@extends('layout.app')

@section('conten')

<!-- Modal para elegir prefijo -->
<div class="modal fade" id="prefijoModal" tabindex="-1" aria-labelledby="prefijoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="prefijoModalLabel">@lang('Seleccione los primeros 7 dígitos')</h5>
      </div>
      <div class="modal-body text-center">
        <p class="fw-semibold">@lang('Elija una opción:')</p>
        <div class="d-grid gap-2">
          <button type="button" class="btn btn-outline-success prefijo-btn" data-prefijo="HE0EA2A">HE0EA2A</button>
          <button type="button" class="btn btn-outline-success prefijo-btn" data-prefijo="HMDNA2A">HMDNA2A</button>
          <button type="button" class="btn btn-outline-success prefijo-btn" data-prefijo="HMDMA2A">HMDMA2A</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="text-center my-4">
    <h3>
        @lang('Crear Bicicleta')
        <span class="badge rounded-pill text-bg-success">@lang('Nueva')</span>
    </h3>
</div>

@if (session('success'))
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16" role="img" aria-label="success:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08-.02l3.99-4.99a.75.75 0 1 0-1.08-1.04L7.5 9.585 5.97 8.06a.75.75 0 0 0-1.08 1.04l2.08 1.93z"/>
            </svg>
            <small class="fw-semibold">{{ session('success') }}</small>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16" role="img" aria-label="error:">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 11a.5.5 0 0 1-.5-.5V8.707L5.354 6.561a.5.5 0 1 1 .707-.707L8 7.293l1.939-1.939a.5.5 0 1 1 .707.707L8.5 8.707V11.5a.5.5 0 0 1-.5.5z"/>
            </svg>
            <small class="fw-semibold">{{ session('error') }}</small>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('Bicicleta.store') }}" class="row g-3 was-validated" id="formBicicleta">
    @csrf

    <div class="col-md-6">
        <label for="num_chasis" class="form-label">@lang('Número de Chasis (17 dígitos)')</label>
        <input type="text" name="num_chasis" id="num_chasis" maxlength="17" class="form-control" placeholder="@lang('Ingrese número de chasis completo')" required autocomplete="off">
        <div class="form-text">@lang('Debe ingresar los 17 caracteres del número de chasis.')</div>
    </div>

    <div class="col-md-6">
        <label for="id_modelo" class="form-label">@lang('Seleccione un modelo')</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required>
            <option value="">@lang('Seleccione un modelo')</option>
            @foreach($modelos as $modelo)
                <option value="{{ $modelo->id_modelo }}">{{ $modelo->nombre_modelo }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 mt-3 text-center">
        <button type="submit" class="btn btn-outline-success">@lang('Guardar Bicicleta')</button>
    </div>

    <div class="col text-end">
        <a href="{{ route('Bicicleta.ver') }}" class="btn btn-outline-success">@lang('Ver Bicis')</a>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {

    let prefijoElegido = '';

    // Mostrar modal al cargar
    var modalPrefijo = new bootstrap.Modal(document.getElementById('prefijoModal'), {
        backdrop: 'static',
        keyboard: false
    });
    modalPrefijo.show();

    // Cuando el usuario elige un prefijo
    $('.prefijo-btn').on('click', function(){
        prefijoElegido = $(this).data('prefijo');
        $('#num_chasis').val(prefijoElegido);

        modalPrefijo.hide();

        // Colocar cursor justo después del prefijo
        setTimeout(function(){
            let input = document.getElementById('num_chasis');
            input.setSelectionRange(prefijoElegido.length, prefijoElegido.length);
            input.focus();
        }, 100);
    });

    // Evitar que el usuario modifique o borre el prefijo
    $('#num_chasis').on('input', function(){
        let valor = $(this).val();
        if (!valor.startsWith(prefijoElegido)) {
            $(this).val(prefijoElegido);
        }
    });

    // Prevenir borrar el prefijo con teclas
    $('#num_chasis').on('keydown', function(e){
        let pos = this.selectionStart;
        if ((pos <= prefijoElegido.length) && (e.key === 'Backspace' || e.key === 'Delete')) {
            e.preventDefault();
        }
    });

    // Cargar colores y voltajes cuando se seleccione el modelo
    $('#id_modelo').on('change', function () {
        let idModelo = $(this).val();

        if (!idModelo) {
            $('#id_color').html('<option value="">@lang("Seleccione un color")</option>');
            $('#id_voltaje').html('<option value="">@lang("Seleccione un voltaje")</option>');
            return;
        }

        // Cargar colores
        let urlColores = '{{ route("Bicicleta.ptoEmilioNoleMuevas", ["id_modelo" => ":id"]) }}'.replace(':id', idModelo);
        $.get(urlColores, function (colores) {
            let opciones = '<option value="">@lang("Seleccione un color")</option>';
            $.each(colores, function (i, color) {
                opciones += `<option value="${color.id_colorM}">${color.nombre_color}</option>`;
            });
            $('#id_color').html(opciones);
        }).fail(function () {
            $('#id_color').html('<option value="">@lang("Error al cargar colores")</option>');
        });

        // Cargar voltajes
        let urlVoltajes = '{{ route("voltaje.porModelo", ["id_modelo" => ":id"]) }}'.replace(':id', idModelo);
        $.get(urlVoltajes, function (voltajes) {
            let opciones = '<option value="">@lang("Seleccione un voltaje")</option>';
            $.each(voltajes, function (i, v) {
                opciones += `<option value="${v.id_voltaje}">${v.tipo_voltaje ?? v.id_voltaje}</option>`;
            });
            $('#id_voltaje').html(opciones);
        }).fail(function () {
            $('#id_voltaje').html('<option value="">@lang("Error al cargar voltajes")</option>');
        });
    });

});
</script>

@endsection
