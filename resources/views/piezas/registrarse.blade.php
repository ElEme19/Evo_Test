@extends('layout.app2')
@section('fondo')

<div class="w-100">
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.webp') }}" alt="EvoBike Logo" class="register-logo mb-3" style="height: 60px;">
        <h3 class="fw-bold mb-1">Registro de cuenta</h3>
        <p class="text-muted">Completa tus datos para crear una cuenta</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('registrar') }}">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="nombre_user" class="form-control" placeholder="Nombre" value="{{ old('nombre_user') }}" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="apellido_usuario" class="form-control" placeholder="Apellido" value="{{ old('apellido_usuario') }}" required>
            </div>
            <div class="col-12">
                <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" value="{{ old('correo') }}" required>
            </div>
            <div class="col-md-6">
                <input type="password" name="user_pass" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="col-md-6">
                <input type="password" name="user_pass_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
            Registrar cuenta
        </button>

        <div class="text-center mt-3">
            <p class="text-muted">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Inicia sesión</a></p>
        </div>
    </form>
</div>



<style>
    .register-form-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .register-logo {
        height: 80px;
        margin-bottom: 1.5rem;
    }
    
    .form-floating {
        margin-bottom: 1rem;
    }
    
    .form-floating label {
        color: #6c757d;
    }
    
    .form-control {
    height: 3.2rem;
    font-size: 0.95rem;
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
}

.form-control:focus {
    border-color: #4DB53F;
    box-shadow: 0 0 0 0.25rem rgba(77, 181, 63, 0.25);
}

.btn-success {
    background-color: #4DB53F;
    border-color: #4DB53F;
}

.btn-success:hover {
    background-color: #3a9a2d;
    border-color: #3a9a2d;
}
    
    .btn-primary {
        background-color: #4DB53F;
        border-color: #4DB53F;
    }
    
    .btn-primary:hover {
        background-color: #3a9a2d;
        border-color: #3a9a2d;
    }
    
    .alert {
        border-radius: 0.5rem;
    }
</style>

@endsection