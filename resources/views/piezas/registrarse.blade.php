@extends('layout.app')
@section('fondo')

<div class="register-form-container">
    <form method="POST" action="{{ route('registrar') }}" class="register-form">
        @csrf
        
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.webp') }}" alt="EvoBike Logo" class="register-logo">
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
        
        <h2 class="text-center mb-1 fw-bold">Registro de cuenta</h2>
        <p class="text-center text-muted mb-4">Completa tus datos para crear una cuenta</p>
        
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" name="nombre_user" id="nombre_user" class="form-control" placeholder="Nombre" value="{{ old('nombre_user') }}" required>
                    <label for="nombre_user">Nombre</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" name="apellido_usuario" id="apellido_usuario" class="form-control" placeholder="Apellido" value="{{ old('apellido_usuario') }}" required>
                    <label for="apellido_usuario">Apellido</label>
                </div>
            </div>
            
            <div class="col-12">
                <div class="form-floating">
                    <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico" value="{{ old('correo') }}" required>
                    <label for="correo">Correo electrónico</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="password" name="user_pass" id="user_pass" class="form-control" placeholder="Contraseña" required>
                    <label for="user_pass">Contraseña</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
                    <label for="confirm_password">Confirmar contraseña</label>
                </div>
            </div>
            
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                    Registrar cuenta
                </button>
            </div>
            
            <div class="col-12 text-center mt-3">
                <p class="text-muted mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Inicia sesión</a></p>
            </div>
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
        height: calc(3.5rem + 2px);
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
    }
    
    .form-control:focus {
        border-color: #4DB53F;
        box-shadow: 0 0 0 0.25rem rgba(77, 181, 63, 0.25);
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