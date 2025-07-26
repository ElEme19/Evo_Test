@extends('layout.app2')
@section('fondo')

<div class="w-100">
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo" style="height: 60px;" class="mb-3">
        <h3 class="fw-bold mb-1">Inicio de sesión</h3>
        <p class="text-muted">Ingresa tus credenciales para acceder</p>
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

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="text" name="correo" id="correo" class="form-control" placeholder="user@evobike.com" required>
        </div>

        <div class="mb-4">
            <label for="user_pass" class="form-label">Contraseña</label>
            <input type="password" name="user_pass" id="user_pass" class="form-control" placeholder="*********" required>
        </div>

        <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
            Entrar
        </button>

        <div class="text-center mt-3">
            <p class="text-muted mb-0">¿Aún no tienes cuenta? 
                <a href="{{ route('registrarse') }}" class="text-decoration-none fw-bold">Registro</a>
            </p>
        </div>
    </form>
</div>




<style>
    .login-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .login-logo {
        max-width: 100%;
        height: auto;
        margin-bottom: 2rem;
        display: block;
    }
    
    .login-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
        color: #6c757d;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #495057;
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

    
    .login-btn {
        width: 100%;
        padding: 0.75rem;
        background-color: #4DB53F;
        color: white;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: background-color 0.15s ease-in-out;
    }
    
    .login-btn:hover {
        background-color: #3a9a2d;
    }
    
    .error-alert {
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .error-list {
        margin-bottom: 0;
        padding-left: 1rem;
    }
</style>

@endsection