@extends('layout.app')

@section('conten')

<div class="text-center my-4">
    <h3>
        @lang('Imprimir Bicicletas por Fecha y Modelo')
        <span class="badge rounded-pill text-bg-primary">@lang('ImprimirTodo')</span>
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="POST" action="{{ route('Bicicleta.imprimirTodo.post') }}" class="card p-4 shadow-sm">
            @csrf
            <div class="mb-3">
                <label for="fecha" class="form-label">@lang('Selecciona una fecha')</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>

            <div class="mb-3">
                <label for="id_modelo" class="form-label">@lang('Selecciona un modelo')</label>
                <select class="form-select" id="id_modelo" name="id_modelo">
                    <option value="">@lang('Todos los modelos')</option>
                    @foreach($modelos as $modelo)
                        <option value="{{ $modelo->id_modelo }}">{{ $modelo->nombre_modelo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">@lang('Imprimir Bicicletas')</button>
            </div>
        </form>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h5 class="text-center">@lang('Resultados de impresión')</h5>
        <p class="text-center small text-muted" id="infoSeleccion"></p>

        <div id="resultados" class="table-responsive mt-3" style="display: none;">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>@lang('Núm. Chasis')</th>
                        <th>@lang('Modelo')</th>
                        <th>@lang('Estado')</th>
                        <th>@lang('Mensaje')</th>
                    </tr>
                </thead>
                <tbody id="tablaResultados"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();

        let form  = $(this);
        let fecha = $('#fecha').val();
        let modeloNombre = $('#id_modelo option:selected').text();

        $('#resultados').hide();
        $('#tablaResultados').empty();
        $('#infoSeleccion').text('');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                if (res.resultados && res.resultados.length > 0) {
                    // Mostrar info de filtros aplicados
                    let modeloTexto = modeloNombre && modeloNombre !== "Todos los modelos"
                        ? modeloNombre
                        : 'Todos los modelos';
                    $('#infoSeleccion').text(`Fecha: ${res.fecha} | Modelo: ${modeloTexto}`);

                    // Pintar filas de resultados
                    $.each(res.resultados, function(i, bici) {
                        let fila = `
                            <tr>
                                <td>${bici.num_chasis}</td>
                                <td>${bici.modelo}</td>
                                <td>
                                    <span class="badge bg-${bici.status === 'success' ? 'success' : 'danger'}">
                                        ${bici.status}
                                    </span>
                                </td>
                                <td>${bici.message}</td>
                            </tr>`;
                        $('#tablaResultados').append(fila);
                    });

                    $('#resultados').show();
                } else {
                    alert('No se encontraron bicicletas para los criterios seleccionados.');
                }
            },
            error: function(err) {
                console.error(err);
                alert('Error al procesar la impresión.');
            }
        });
    });
});
</script>

@endsection
