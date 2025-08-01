@extends('layout.app')

@section('conten')

<div class="text-center my-4">
    <h3>
        Piezas
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
            <small class="fw-semibold">
                {{ session('success') }}
            </small>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong>Corrige los errores:</strong>
            <ul class="mb-0 ms-2 text-start">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form id="formulario-pieza" class="row g-3 was-validated" method="POST" action="{{ route('pieza.store') }}" enctype="multipart/form-data" novalidate>
    @csrf

    <div class="col-md-4">
        <label for="id_modelo" class="form-label">Modelo</label>
        <select class="form-select" name="id_modelo" id="id_modelo" required>
            <option value="">Selecciona un modelo</option>
            @foreach($modelos as $modelo)
                <option value="{{ $modelo->id_modelo }}" {{ old('id_modelo') == $modelo->id_modelo ? 'selected' : '' }}>
                    {{ $modelo->nombre_modelo ?? 'Sin nombre' }}
                </option>
            @endforeach
        </select>
        <div class="invalid-feedback">Seleccione un modelo válido.</div>
    </div>

    <div class="col-md-4">
        <label for="nombre_pieza" class="form-label">Nombre de la Pieza</label>
        <input type="text" class="form-control" id="nombre_pieza" name="nombre_pieza"   placeholder="Asigne un Nombre a la pieza"
            value="{{ old('nombre_pieza') }}" required>
        <div class="invalid-feedback">Ingrese el nombre de la pieza.</div>
    </div>

    <div class="col-md-4">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" id="color" name="color"
            placeholder="Ingrese el color" value="{{ old('color') }}" >
        <div class="invalid-feedback">Ingrese un color para la pieza.</div>
    </div>

    <div class="col-md-4">
        <label for="descripcion_general" class="form-label">Descripción General</label>
        <input type="text" class="form-control" id="descripcion_general" name="descripcion_general"
            placeholder="Ingrese una descripción" value="{{ old('descripcion_general') }}" required>
        <div class="invalid-feedback">Ingrese la descripción general.</div>
    </div>

    <div class="col-md-4">
    <label for="Unidad" class="form-label">Unidad</label>
    <select class="form-select" id="Unidad" name="Unidad" required>
        <option value="" disabled {{ old('Unidad') ? '' : 'selected' }}>Seleccione una opción</option>
        <option value="Pz" {{ old('Unidad') == 'Pz' ? 'selected' : '' }}>Pz</option>
        <option value="Par" {{ old('Unidad') == 'Par' ? 'selected' : '' }}>Par</option>
    </select>
    <div class="invalid-feedback">Seleccione la unidad correspondiente.</div>
    </div>



    <div class="col-md-4">
        <label for="foto_pieza" class="form-label">Foto de la Pieza</label>
        <input type="file" class="form-control" id="foto_pieza" name="foto_pieza" accept="image/*">
        <div class="form-text">Formatos permitidos: JPG, PNG. Máx: 2MB.</div>
    </div>

    <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">
                Guardar Pieza
            </button>
        </div>
        <div class="col text-end">
            <a href="{{ route('pieza.ver') }}" class="btn btn-outline-success">
                Ver Piezas
            </a>
        </div>
    </div>
</form>

@endsection
