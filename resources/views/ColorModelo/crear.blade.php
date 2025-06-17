@extends ('layout.app')

@section('title')

@section ('conten')

 <div class="text-center my-4">
        <h3>
            Color Modelo 
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


<form class="row g-3 was-validated" method="POST" action="{{ route('Color.store') }}">
            @csrf

            <div class="col-md-4">
                <label for="validationServer01" class="form-label">ID Color</label>
                <input type="text" class="form-control is-valid" id="id_colorM"  name="id_colorM" placeholder="Required ID Color" required>
                <div class="invalid-feedback">Ingrese el Id.</div>
            </div>

                <div class="col-md-4">
                <label for="id_modelo" class="form-label">ID Modelo</label>
                <select class="form-select" name="id_modelo" id="id_modelo" required>
                    <option value="">Selecciona un modelo</option>
                    @foreach($modelos as $modelo)
                        <option value="{{ $modelo->id_modelo }}"> {{ $modelo->nombre_modelo ?? 'Sin nombre' }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Seleccione un modelo válido.</div>
                </div>
             
            <div class="col-md-4">
                <label for="validationServer02" class="form-label">Nombre Color</label>
                <input type="text" class="form-control is-valid" id="nombre_color" name="nombre_color" placeholder="Required ID Color" required>
                <div class="invalid-feedback">Ingrese un nombre.</div>
            </div>

            <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">
                Guardar Color
            </button>
        </div>
             <div class="col text-end">
            <a href="{{ route('Color.vista') }}" class="btn btn-outline-success">
                Ver Colores
            </a>
        </div>


@endsection


