@extends('layout.app')

@section('title')

@section('conten')

    <div class="text-center my-4">
        <h3>
            Lote 
            <span class="badge rounded-pill text-bg-success">Nuevo</span>
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

<form class="row g-3 was-validated" method="POST" action="{{ route('Lote.store') }}">
    @csrf

    <div class="col-md-6">
    <label for="id_lote" class="form-label">ID Lote</label>
    <input type="text" class="form-control" id="id_lote" name="id_lote" placeholder="Ej: LOT001" required>
    <div class="invalid-feedback">Ingrese un ID válido para el lote.</div>
</div>


    <div class="col-md-6">
        <label for="fecha_produccion" class="form-label">Fecha de Producción</label>
        <input type="date" class="form-control" id="fecha_produccion" name="fecha_produccion" required>
        <div class="invalid-feedback">Ingrese una fecha válida.</div>
    </div>

    <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">
                Guardar Lote
            </button>
        </div>
        <div class="col text-end">
            <a href="{{ route('Lote.vista') }}" class="btn btn-outline-success">
                Ver Lotes
            </a>
        </div>
    </div>
</form>

@endsection
