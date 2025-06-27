@extends('layout.app')

@section('conten')
@php
    $user = Auth::guard('usuarios')->user();
@endphp

<div class="container-xxl py-5">
    @if($user)
        <!-- Hero Section -->
        <div class="text-center mb-5">
            
            <h1 class="display-5 fw-bold text-dark mb-3">Bienvenido a  <img src="{{ asset('images/logo_ev01.webp') }}" alt="CloudLabs Logo" style="height: 40px;" class="mb-1">v.2</h1>
            <p class="lead text-secondary">Sistema integral para administración de movilidad sustentable</p>
            
            <div class="user-badge bg-light p-3 rounded-pill shadow-sm d-inline-flex align-items-center mt-3">
                <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle me-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <span class="fw-medium text-dark">{{ $user->nombre_user }}</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-2">{{ $user->tipo_dia }}</span>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark fw-semibold"><i class="fas fa-info-circle text-warning me-2"></i>Estado del Sistema</h5>
                            <span class="badge bg-warning bg-opacity-20 text-dark">Versión Beta</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-3">Estamos mejorando tu experiencia</h4>
                                <p class="text-muted mb-4">La plataforma se encuentra en fase de desarrollo activo. Agradecemos tu paciencia mientras implementamos nuevas funcionalidades.</p>
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: 48%"></div>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark">48%</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center d-none d-md-block">
                                <img src="{{ asset('images/cloudL.png') }}" alt="CloudLabs Logo" style="height: 150px;" class="mb-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-4" style="width: 60px; height: 60px;">
                            <i class="fas fa-bicycle fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Gestión de Flota</h4>
                        <p class="text-muted mb-4">Administra tu inventario de bicicletas con herramientas avanzadas de seguimiento y mantenimiento.</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Explorar <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-4" style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Analíticas Avanzadas</h4>
                        <p class="text-muted mb-4">Visualiza métricas clave y toma decisiones basadas en datos en tiempo real.</p>
                        <a href="#" class="btn btn-sm btn-outline-success">Ver reportes <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-4" style="width: 60px; height: 60px;">
                            <i class="fas fa-cogs fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Configuración</h4>
                        <p class="text-muted mb-4">Personaliza la plataforma según las necesidades específicas de tu operación.</p>
                        <a href="#" class="btn btn-sm btn-outline-info">Configurar <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>

       

    @else
        <!-- Guest View -->
        <div class="auth-wrapper py-5">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <img src="{{ asset('images/evobike-logo.png') }}" alt="Evobike Logo" style="height: 50px;" class="mb-4">
                                <h2 class="h4 text-dark mb-3">Acceso al Sistema</h2>
                                <p class="text-muted">Ingresa tus credenciales para acceder al panel de gestión</p>
                            </div>
                            
                            <form class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" placeholder="tu@email.com" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <div class="d-grid mb-3">
                                    <button class="btn btn-primary py-2" type="submit">
                                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                    </button>
                                </div>
                                <div class="text-center">
                                    <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .avatar {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card {
        transition: transform 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
    }
    
    .user-badge {
        transition: all 0.2s ease;
    }
    
    .user-badge:hover {
        background-color: #f8f9fa !important;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
</style>

@endsection