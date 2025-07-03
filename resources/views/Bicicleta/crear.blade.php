@extends('layout.app')

@section('conten')

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
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
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
        <label for="num_chasis_parcial" class="form-label">@lang('Últimos 4 Dígitos de Número de Chasis')</label>
        <input type="text" name="num_chasis_parcial" id="num_chasis_parcial" maxlength="4" class="form-control" placeholder="@lang('Ingrese últimos 4 dígitos')" required autocomplete="off">
        <input type="hidden" name="num_chasis" id="num_chasis_full" value="">
        <div class="form-text">@lang('Al ingresar los últimos 4 dígitos, se buscará automáticamente la bicicleta.')</div>
    </div>

    <div class="col-md-6">
        <label for="num_chasis_mostrar" class="form-label">@lang('Número de Chasis completo')</label>
        <input type="text" id="num_chasis_mostrar" class="form-control" readonly placeholder="@lang('Será llenado automáticamente')" disabled>
    </div>

    <div class="col-md-6">
        <label for="id_modelo" class="form-label">@lang('Seleccione un modelo')</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required disabled>
            <option value="">@lang('Seleccione un modelo')</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="id_color" class="form-label">@lang('Color (Disponible según modelo)')</label>
        <select name="id_color" id="id_color" class="form-select" required>
            <option value="">@lang('Seleccione un color')</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_lote" class="form-label">@lang('Lote')</label>
        <select name="id_lote" class="form-select" required>
            @foreach($lotes as $lote)
                <option value="{{ $lote->id_lote }}">{{ $lote->fecha_produccion }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_tipoStock" class="form-label">@lang('Tipo de Stock')</label>
        <input type="text" class="form-control" value="{{ $tipos->firstWhere('id_tipoStock', 'STK000')->nombre_stock ?? 'STK000' }}" disabled>
        <input type="hidden" name="id_tipoStock" value="STK000">
    </div>

    <div class="col-md-4">
        <label for="id_voltaje" class="form-label">@lang('Voltaje (Disponible según modelo)')</label>
        <select name="id_voltaje" id="id_voltaje" class="form-select" required>
            <option value="">@lang('Seleccione un voltaje')</option>
        </select>
    </div>

    {{-- Botón de guardar eliminado --}}

    <div class="col text-end">
        <a href="{{ route('Bicicleta.ver') }}" class="btn btn-outline-success">@lang('Ver Bicis')</a>
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-labelledby="modalResultadoBusquedaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Resultado de la búsqueda')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Cerrar')"></button>
            </div>
            <div class="modal-body" id="modalBodyMensaje"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">@lang('Ok')</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    var modalResultadoBusqueda = new bootstrap.Modal(document.getElementById('modalResultadoBusqueda'));
    var modalBodyMensaje      = $('#modalBodyMensaje');

    function mostrarAlertaModal(tipo, mensaje) {
        const color    = tipo === 'success' ? 'success' : 'danger';
        const iconPath = tipo === 'success'
            ? 'M16 8A8 8 0 1 1 0 8a8...'    // check
            : 'M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165...'; // error

        const html = `
        <div class="text-center">
            <div class="alert alert-${color} d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16" role="img" aria-label="${tipo}:">
                    <path d="${iconPath}"/>
                </svg>
                <small class="fw-semibold">${mensaje}</small>
            </div>
        </div>`;

        modalBodyMensaje.html(html);
        modalResultadoBusqueda.show();
    }

    function resetModeloYBoton() {
        $('#num_chasis_full').val('');
        $('#num_chasis_mostrar').val('');
        $('#id_modelo')
            .html('<option value="">@lang("Seleccione un modelo")</option>')
            .prop('disabled', false)
            .trigger('change');
        $('#id_color').html('<option value="">@lang("Seleccione un color")</option>');
        $('#id_voltaje').html('<option value="">@lang("Seleccione un voltaje")</option>');
    }

    $('#num_chasis_parcial').on('input', function () {
        let ult4 = $(this).val();

        if (ult4.length === 4) {
            $.ajax({
                url: '{{ route("Bicicleta.buscarUltimos4") }}',
                method: 'GET',
                data: { ult4: ult4 },
                success: function (response) {
                    if (response.success) {
                        let bici         = response.bici;
                        let modeloId     = bici.id_modelo;
                        let modeloNombre = bici.modelo ? bici.modelo.nombre_modelo : 'Modelo desconocido';

                        $('#id_modelo')
                            .html(`<option value="${modeloId}" selected>${modeloNombre}</option>`)
                            .prop('disabled', true)
                            .trigger('change');

                        $('#num_chasis_full').val(bici.num_chasis);
                        $('#num_chasis_mostrar').val(bici.num_chasis);

                        mostrarAlertaModal('success', '@lang("Bicicleta encontrada y modelo preseleccionado.")');

                        cargarColores(modeloId, function() {
                            $('#id_color option:eq(1)').prop('selected', true); // Primer color disponible
                        });

                        cargarVoltajes(modeloId, function() {
                            $('#id_voltaje option:eq(1)').prop('selected', true); // Primer voltaje disponible
                        });

                        // Enviar formulario automáticamente tras esperar cargado de selects
                        setTimeout(() => {
                            $('#formBicicleta').submit();
                        }, 1000);
                    } else {
                        mostrarAlertaModal('danger', response.message);
                        resetModeloYBoton();
                    }
                },
                error: function () {
                    mostrarAlertaModal('danger', '@lang("Error al buscar la bicicleta, intentá de nuevo.")');
                    resetModeloYBoton();
                }
            });
        } else {
            resetModeloYBoton();
        }
    });

    function cargarColores(idModelo, callback) {
        let urlColores = '{{ route("Bicicleta.ptoEmilioNoleMuevas", ["id_modelo" => ":id"]) }}'.replace(':id', idModelo);
        $.get(urlColores, function(colores) {
            let opciones = '<option value="">@lang("Seleccione un color")</option>';
            $.each(colores, function(i, color) {
                opciones += `<option value="${color.id_colorM}">${color.nombre_color}</option>`;
            });
            $('#id_color').html(opciones);
            if (typeof callback === 'function') callback();
        }).fail(function() {
            $('#id_color').html('<option value="">@lang("Error al cargar colores")</option>');
        });
    }

    function cargarVoltajes(idModelo, callback) {
        let urlVoltajes = '{{ route("voltaje.porModelo", ["id_modelo" => ":id"]) }}'.replace(':id', idModelo);
        $.get(urlVoltajes, function(voltajes) {
            let opciones = '<option value="">@lang("Seleccione un voltaje")</option>';
            $.each(voltajes, function(i, v) {
                opciones += `<option value="${v.id_voltaje}">${v.tipo_voltaje ?? v.id_voltaje}</option>`;
            });
            $('#id_voltaje').html(opciones);
            if (typeof callback === 'function') callback();
        }).fail(function() {
            $('#id_voltaje').html('<option value="">@lang("Error al cargar voltajes")</option>');
        });
    }
});
</script>

@endsection
