@extends('layout.app')

@section('conten')

<div class="text-center my-4">
    <h3>
        Crear Bicicleta
        <span class="badge rounded-pill text-bg-success">Nueva</span>
    </h3>
</div>

@if (session('success'))
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
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
        <label for="num_chasis" class="form-label">Últimos 4 Dígitos de Número de Chasis</label>
        <input type="text" name="num_chasis_parcial" id="num_chasis_parcial" maxlength="4" class="form-control" placeholder="Ingrese últimos 4 dígitos" required autocomplete="off">
        <input type="hidden" name="num_chasis" id="num_chasis_full" value="">
        <div class="form-text">Al ingresar los últimos 4 dígitos, se buscará automáticamente la bicicleta.</div>
    </div>

    <div class="col-md-6">
        <label for="id_modelo" class="form-label">Modelo (Asignado automáticamente)</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required>
            <option value="">Seleccione un modelo</option>
            @foreach($modelos as $modelo)
                <option value="{{ $modelo->id_modelo }}">{{ $modelo->nombre_modelo }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="id_color" class="form-label">Color (Disponible según modelo)</label>
        <select name="id_color" id="id_color" class="form-select" required>
            <option value="">Seleccione un color</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_lote" class="form-label">Lote</label>
        <select name="id_lote" class="form-select" required>
            @foreach($lotes as $lote)
                <option value="{{ $lote->id_lote }}">{{ $lote->fecha_produccion }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_tipoStock" class="form-label">Tipo de Stock</label>
        <select class="form-select" disabled>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id_tipoStock }}" {{ $tipo->id_tipoStock === 'STK000' ? 'selected' : '' }}>
                    {{ $tipo->nombre_stock }}
                </option>
            @endforeach
        </select>
        <input type="hidden" name="id_tipoStock" value="STK000">
    </div>

    <div class="col-12 mt-3 text-center">
        <button type="submit" class="btn btn-outline-success" id="btnGuardar" disabled>Guardar Bicicleta</button>
    </div>

    <div class="col text-end">
        <a href="{{ route('Bicicleta.ver') }}" class="btn btn-outline-success">Ver Bicis</a>
    </div>
</form>

<!-- Modal de resultado búsqueda -->
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-labelledby="modalResultadoBusquedaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resultado de la búsqueda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalBodyMensaje"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const numChasisInput = document.getElementById('num_chasis_parcial');
    const modeloSelect = document.getElementById('id_modelo');
    const colorSelect = document.getElementById('id_color');
    const btnGuardar = document.getElementById('btnGuardar');
    const numChasisFullInput = document.getElementById('num_chasis_full');
    const modalResultadoBusqueda = new bootstrap.Modal(document.getElementById('modalResultadoBusqueda'));
    const modalBodyMensaje = document.getElementById('modalBodyMensaje');

    function cargarColores(modeloId, colorSeleccionado = null) {
        if (!modeloId) {
            colorSelect.innerHTML = '<option value="">Seleccione un color</option>';
            colorSelect.disabled = true;
            return;
        }
        fetch(`/colores-por-modelo/${modeloId}`)
            .then(res => res.json())
            .then(colores => {
                colorSelect.innerHTML = '<option value="">Seleccione un color</option>';
                colores.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id_colorM;
                    opt.textContent = c.nombre_color;
                    colorSelect.appendChild(opt);
                });
                colorSelect.disabled = false;
                if (colorSeleccionado) {
                    colorSelect.value = colorSeleccionado;
                }
            })
            .catch(() => {
                colorSelect.innerHTML = '<option value="">Error al cargar colores</option>';
                colorSelect.disabled = true;
            });
    }

    numChasisInput.addEventListener('input', () => {
        const val = numChasisInput.value.trim();
        if (val.length === 4) {
            fetch(`/Bicicleta/buscar-por-ultimos4?ult4=${val}`)
                .then(res => res.json())
                .then(data => {
                    if (data.bici) {
                        modeloSelect.value = data.bici.id_modelo;
                        cargarColores(data.bici.id_modelo, data.bici.id_color);
                        numChasisFullInput.value = data.bici.num_chasis;
                        btnGuardar.disabled = false;
                        modalBodyMensaje.innerHTML = `
                            <p><strong>¡Número de serie encontrado!</strong></p>
                            <p><strong>Chasis:</strong> ${data.bici.num_chasis}</p>`;
                    } else {
                        modeloSelect.value = "";
                        cargarColores(modeloSelect.value);
                        numChasisFullInput.value = '';
                        btnGuardar.disabled = false;
                        modalBodyMensaje.innerHTML = `<p><strong>No se encontró ningún número de serie con esos últimos 4 dígitos.</strong></p>`;
                    }
                    modalResultadoBusqueda.show();
                })
                .catch(() => {
                    modalBodyMensaje.innerHTML = `<p><strong>Error al buscar bicicleta.</strong></p>`;
                    modalResultadoBusqueda.show();
                });
        } else {
            modeloSelect.value = "";
            colorSelect.innerHTML = '<option value="">Seleccione un color</option>';
            colorSelect.disabled = true;
            numChasisFullInput.value = '';
            btnGuardar.disabled = true;
        }
    });

    modeloSelect.addEventListener('change', () => {
        cargarColores(modeloSelect.value);
    });

    document.getElementById('formBicicleta').addEventListener('submit', () => {
        modeloSelect.disabled = false;
        colorSelect.disabled = false;
    });
});
</script>

@endsection
