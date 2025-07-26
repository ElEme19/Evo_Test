@extends('layout.app2')
@section('fondo')

<div class="text-center mb-4">
    <img src="{{ asset('images/logo.webp') }}" alt="EvoBike Logo" class="auth-logo">
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

<h2 class="auth-title text-center">Registro de cuenta</h2>
<p class="auth-subtitle text-center">Completa tus datos para crear una cuenta</p>

<form method="POST" action="{{ route('registrar') }}">
    @csrf
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" name="nombre_user" id="nombre_user" class="form-control" 
                       placeholder="Nombre" value="{{ old('nombre_user') }}" required>
                <label for="nombre_user">Nombre</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" name="apellido_usuario" id="apellido_usuario" class="form-control" 
                       placeholder="Apellido" value="{{ old('apellido_usuario') }}" required>
                <label for="apellido_usuario">Apellido</label>
            </div>
        </div>
        
        <div class="col-12">
            <div class="form-floating">
                <input type="email" name="correo" id="correo" class="form-control" 
                       placeholder="Correo electrónico" value="{{ old('correo') }}" required>
                <label for="correo">Correo electrónico</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" name="user_pass" id="user_pass" class="form-control" 
                       placeholder="Contraseña" required>
                <label for="user_pass">Contraseña</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                       placeholder="Confirmar contraseña" required>
                <label for="confirm_password">Confirmar contraseña</label>
            </div>
        </div>
        
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary w-100 py-3">
                <i class="fas fa-user-plus me-2"></i> Registrar cuenta
            </button>
        </div>
        
        <div class="col-12 auth-footer">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="auth-link">Inicia sesión</a>
        </div>
    </div>
</form>

@endsection