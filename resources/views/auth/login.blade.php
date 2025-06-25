@extends('layout.app2')
@section('fondo')

<div class="login-container">
    <form method="POST" action="{{route('login')}}" class="login-form">
        @csrf
        
        <div class="logo-container">
            <img src="images/logo.webp" alt="Logo" class="login-logo">
        </div>
        
        @if ($errors->any())
        <div class="alert alert-danger error-alert">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <h2 class="login-title">Inicio de sesión</h2>
        <p class="login-subtitle">Ingresa tus credenciales para acceder</p>
        
        <div class="form-group">
            <label for="form2Example17" class="form-label">Correo electrónico</label>
            <input type="text" name="user_name" id="form2Example17" class="form-control" placeholder="user@evobike.com" />
        </div>
        
        <div class="form-group">
            <label for="form2Example27" class="form-label">Contraseña</label>
            <input type="password" name="user_pass" id="form2Example27" class="form-control" placeholder="*********" />
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn login-btn">
                Entrar
            </button>
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
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: #4DB53F;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(77, 181, 63, 0.25);
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