@extends('layout.app')

@section('conten')

<!-- Modal para elegir prefijo -->
<div class="modal fade" id="prefijoModal" tabindex="-1" aria-labelledby="prefijoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="prefijoModalLabel">@lang('Seleccione prefijo')</h5>
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
    <h3>@lang('Prueba de asignación de modelo')</h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <label for="num_chasis" class="form-label">@lang('Número de Chasis')</label>
        <input type="text" id="num_chasis" maxlength="17" class="form-control" placeholder="@lang('Ingrese número de chasis completo')">

        <div class="form-text mt-2">@lang('Se mostrará el modelo detectado automáticamente.')</div>
    </div>
</div>

<div class="text-center mt-3">
    <h5>Modelo detectado: <span id="modeloDetectado" class="fw-bold text-success">-</span></h5>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    let prefijoElegido = '';

    // Mostrar modal al cargar
    let modalPrefijo = new bootstrap.Modal(document.getElementById('prefijoModal'), {
        backdrop: 'static',
        keyboard: false
    });
    modalPrefijo.show();

    // Elegir prefijo
    $('.prefijo-btn').on('click', function(){
        prefijoElegido = $(this).data('prefijo');
        $('#num_chasis').val(prefijoElegido);
        modalPrefijo.hide();

        setTimeout(function(){
            let input = document.getElementById('num_chasis');
            input.setSelectionRange(prefijoElegido.length, prefijoElegido.length);
            input.focus();
        }, 100);
    });

    // Evitar borrar prefijo
    $('#num_chasis').on('input', function(){
        let valor = $(this).val().toUpperCase();
        if (!valor.startsWith(prefijoElegido)) {
            $(this).val(prefijoElegido);
        }

        // Detectar modelo automáticamente
        if (valor.length >= 13) {
            let regex = /^(HE0EA2A|HMDNA2A|HMDMA2A)0.{3}(\d{2})\d{4}$/;
            let matches = valor.match(regex);
            if (matches) {
                let codigo = matches[2];
                let mapaModelos = {
                    '14': 'Zeus',
                    '05': 'Galaxy',
                    '03': 'Primavera',
                    '19': 'Reina',
                    '09': 'VMP S5',
                    '06': 'Rayo',
                    '11': 'Polar',
                    '24': 'Urbex',
                    '18': 'Eclipce',
                    '07': 'Águila',
                    '08': 'Sol',
                    '16': 'Sol Pro',
                };

                let nombreModelo = mapaModelos[codigo] || '-';
                $('#modeloDetectado').text(nombreModelo);
            } else {
                $('#modeloDetectado').text('-');
            }
        } else {
            $('#modeloDetectado').text('-');
        }
    });

    // Prevenir borrar prefijo con teclas
    $('#num_chasis').on('keydown', function(e){
        let pos = this.selectionStart;
        if ((pos <= prefijoElegido.length) && (e.key === 'Backspace' || e.key === 'Delete')) {
            e.preventDefault();
        }
    });

});
</script>

@endsection
