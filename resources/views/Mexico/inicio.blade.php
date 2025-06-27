@extends('layout.app')

@section('conten')
@php
    $user = Auth::guard('usuarios')->user();
@endphp

<div class="container-xxl py-5">
    @if($user)
        <!-- NUEVA SECCIÓN: Estadísticas -->
<div class="row mb-5">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info bg-opacity-10 border-0">
                <h5 class="mb-0 text-dark fw-semibold"><i class="fas fa-chart-bar me-2 text-info"></i>Estadísticas Generales</h5>
            </div>
            <div class="card-body">
                <div id="revenue-chart" style="height: 300px;"></div>
            </div>
        </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="info-box text-bg-primary shadow-sm">
                    <span class="info-box-icon"><i class="bi bi-truck"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pedidos activos</span>
                        <span class="info-box-number">128</span>
                        <div class="progress"><div class="progress-bar" style="width: 80%"></div></div>
                        <span class="progress-description"> 80% entregados este mes </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box text-bg-success shadow-sm">
                    <span class="info-box-icon"><i class="bi bi-bicycle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bicis Activas</span>
                        <span class="info-box-number">634</span>
                        <div class="progress"><div class="progress-bar" style="width: 65%"></div></div>
                        <span class="progress-description"> En circulación actualmente </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GESTIÓN DE FLOTA: Timeline + Card Expandible -->
<div class="row g-4 mb-5">
    <!-- CARD original de Flota -->
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

    <!-- CARD Expandible -->
    <div class="col-md-6 col-lg-4">
        <div class="card card-primary collapsed-card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title">Estado de Pedidos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline-item">
                    <span class="time"><i class="bi bi-clock-fill"></i> 09:35</span>
                    <h3 class="timeline-header"><a href="#">Sucursal Centro</a> realizó un pedido</h3>
                    <div class="timeline-body">
                        32 bicicletas solicitadas. Se encuentra en revisión por almacén.
                    </div>
                    <div class="timeline-footer">
                        <a class="btn btn-primary btn-sm">Ver detalles</a>
                        <a class="btn btn-danger btn-sm">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración: CARD Expandible -->
    <div class="col-md-6 col-lg-4">
        <div class="card card-info collapsed-card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title">Preferencias de Usuario</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                Puedes personalizar opciones como idioma, notificaciones, y tema visual del sistema.
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chart = new ApexCharts(document.querySelector("#revenue-chart"), {
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false }
        },
        series: [{
            name: 'Ventas',
            data: [10, 41, 35, 51, 49, 62, 69]
        }],
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul']
        },
        colors: ['#0dcaf0'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        }
    });
    chart.render();
});
</script>
@endpush


@endsection