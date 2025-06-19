@extends('layout.app')

@section('title')

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
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                fill="currentColor"
                class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="success:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <small class="fw-semibold">  <!-- Para la alerta -->
                {{ session('success') }}  
            </small>
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


<form method="POST" action="{{ route('Bicicleta.store') }}" class="row g-3 was-validated">
    @csrf

    <div class="col-md-6">
        <label for="num_chasis" class="form-label">Número de Chasis</label>
        <input type="text" name="num_chasis" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="num_motor" class="form-label">Número de Motor</label>
        <input type="text" name="num_motor" class="form-control">
    </div>

    <div class="col-md-6">
        <label for="id_modelo" class="form-label">Modelo</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required>
            <option value="">Seleccione un modelo</option>
            @foreach($modelos as $modelo)
                <option value="{{ $modelo->id_modelo }}">{{ $modelo->nombre_modelo }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="id_color" class="form-label">Color</label>
        <select name="id_color" id="id_color" class="form-select" >
            <option value="">Seleccione un modelo primero</option>
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
        <select name="id_tipoStock" class="form-select" required>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id_tipoStock }}">{{ $tipo->nombre_stock }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="voltaje" class="form-label">Voltaje</label>
        <input type="text" name="voltaje" class="form-control">
    </div>

    <div class="col-md-6">
        <label for="error_iden_produccion" class="form-label">Error Identificación Producción</label>
        <input type="text" name="error_iden_produccion" class="form-control">
    </div>


    <div class="col-12 mt-3 text-center">
        <button type="submit" class="btn btn-outline-success">Guardar Bicicleta</button>
    </div>
     <div class="col text-end">
            <a href="{{ route('Bicicleta.ver') }}" class="btn btn-outline-success">
                Ver Bicis
            </a>
        </div>
</form>

{{-- Script para filtrar colores por modelo --}}
<script>
    document.getElementById('id_modelo').addEventListener('change', function () {
        const modeloId = this.value;
        const colorSelect = document.getElementById('id_color');

        colorSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/colores-por-modelo/${modeloId}`)
            .then(res => res.json())
            .then(colores => {
                colorSelect.innerHTML = '<option value="">Seleccione un color</option>';
                colores.forEach(color => {
                    const opt = document.createElement('option');
                    opt.value = color.id_colorM;
                    opt.textContent = color.nombre_color;
                    colorSelect.appendChild(opt);
                });
            })
            .catch(() => {
                colorSelect.innerHTML = '<option value="">Error al cargar colores</option>';
            });
    });
</script>


@endsection
