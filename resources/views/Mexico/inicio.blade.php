@extends('layout.app')

@section('conten')
@php
    $user = Auth::guard('usuarios')->user();
@endphp

<div class="container-xxl py-5">
    @if($user)
        <!-- Sección de Bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card bg-gradient-primary bg-opacity-10 p-4 rounded-3 border-start border-4 border-primary">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="avatar-wrapper me-md-4 mb-3 mb-md-0">
                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-1">Bienvenido, {{ $user->name }}</h2>
                            <p class="text-muted mb-2">Último acceso: {{ now()->format('d/m/Y H:i') }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-circle me-1 small"></i> Sesión activa
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-user-tag me-1 small"></i> {{ $user->rol }}
                                </span>
                            </div>
                        </div>
                        <div class="weather-widget mt-3 mt-md-0 text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-sun fa-2x text-warning me-2"></i>
                                <div>
                                    <div class="fw-bold">28°C</div>
                                    <small class="text-muted">Madrid</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row mb-5 g-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card bg-white rounded-3 p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold">Bicis Activas</h6>
                            <h3 class="fw-bold mb-0">634</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> 12% este mes
                            </small>
                        </div>
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-bicycle"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 78%"></div>
                        </div>
                        <small class="text-muted">78% en circulación</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card bg-white rounded-3 p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold">Pedidos</h6>
                            <h3 class="fw-bold mb-0">128</h3>
                            <small class="text-danger">
                                <i class="fas fa-arrow-down me-1"></i> 5% esta semana
                            </small>
                        </div>
                        <div class="icon-circle bg-success bg-opacity-10 text-success">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 65%"></div>
                        </div>
                        <small class="text-muted">65% completados</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card bg-white rounded-3 p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold">Clientes</h6>
                            <h3 class="fw-bold mb-0">2,543</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> 24% este trimestre
                            </small>
                        </div>
                        <div class="icon-circle bg-info bg-opacity-10 text-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">85% activos</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card bg-white rounded-3 p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold">Ingresos</h6>
                            <h3 class="fw-bold mb-0">€24,850</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> 32% este mes
                            </small>
                        </div>
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 92%"></div>
                        </div>
                        <small class="text-muted">92% del objetivo</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Estadísticas -->
        <div class="row mb-5 g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line text-primary me-2"></i> Rendimiento Mensual</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                Este año
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Este mes</a></li>
                                <li><a class="dropdown-item" href="#">Este trimestre</a></li>
                                <li><a class="dropdown-item" href="#">Este año</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="performance-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt text-success me-2"></i> Distribución por Zonas</h5>
                    </div>
                    <div class="card-body">
                        <div id="distribution-chart" style="height: 300px;"></div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2" style="width: 12px; height: 12px;"></span>
                                <small class="text-muted">Centro: 42%</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                                <small class="text-muted">Norte: 28%</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2" style="width: 12px; height: 12px;"></span>
                                <small class="text-muted">Sur: 30%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado del Sistema -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark fw-semibold"><i class="fas fa-info-circle text-warning me-2"></i>Estado del Sistema</h5>
                            <span class="badge bg-warning bg-opacity-20 text-dark">Versión Beta 2.1</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-3">Estamos mejorando tu experiencia</h4>
                                <p class="text-muted mb-4">La plataforma se encuentra en fase de desarrollo activo. Próximas actualizaciones incluirán:</p>
                                
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Integración con GPS en tiempo real</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Reportes avanzados de mantenimiento</li>
                                    <li class="mb-2"><i class="fas fa-spinner text-warning me-2"></i> Panel de control para clientes (en desarrollo)</li>
                                    <li><i class="far fa-clock text-muted me-2"></i> App móvil (próximamente)</li>
                                </ul>
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: 48%"></div>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark">48% completado</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center d-none d-md-block">
                                <img src="{{ asset('images/cloudL.png') }}" alt="CloudLabs Logo" style="height: 150px;" class="mb-3">
                                <div class="alert alert-info bg-opacity-10">
                                    <small><i class="fas fa-info-circle me-1"></i> ¿Tienes sugerencias? <a href="#" class="text-info">Cuéntanos</a></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Flota y Pedidos -->
        <div class="row g-4 mb-5">
            <!-- Gestión de Flota -->
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

            <!-- Pedidos Recientes -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-primary shadow-sm h-100">
                    <div class="card-header bg-light border-0">
                        <h3 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i>Pedidos Recientes</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">#ORD-2023-156</h6>
                                        <small class="text-muted">Sucursal Centro - 15 bicis</small>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success">Completado</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">#ORD-2023-157</h6>
                                        <small class="text-muted">Sucursal Norte - 8 bicis</small>
                                    </div>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">En proceso</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">#ORD-2023-158</h6>
                                        <small class="text-muted">Sucursal Sur - 12 bicis</small>
                                    </div>
                                    <span class="badge bg-info bg-opacity-10 text-info">Enviado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0">
                        <a href="#" class="btn btn-sm btn-outline-primary w-100">Ver todos los pedidos</a>
                    </div>
                </div>
            </div>

            <!-- Mantenimientos Pendientes -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-danger shadow-sm h-100">
                    <div class="card-header bg-light border-0">
                        <h3 class="card-title mb-0"><i class="fas fa-tools me-2"></i>Mantenimientos</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">Bici #B2023-045</h6>
                                        <small class="text-muted">Frenos desgastados</small>
                                    </div>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Urgente</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">Bici #B2023-128</h6>
                                        <small class="text-muted">Cambio de rueda trasera</small>
                                    </div>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Prioritario</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">Bici #B2023-312</h6>
                                        <small class="text-muted">Revisión general</small>
                                    </div>
                                    <span class="badge bg-info bg-opacity-10 text-info">Programado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0">
                        <a href="#" class="btn btn-sm btn-outline-danger w-100">Gestionar mantenimientos</a>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Vista para Invitados -->
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
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" class="form-control" id="email" placeholder="tu@email.com" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                        <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <div class="d-grid mb-3">
                                    <button class="btn btn-primary py-2 fw-bold" type="submit">
                                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                    </button>
                                </div>
                                <div class="text-center">
                                    <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-light text-center py-3">
                            <small class="text-muted">¿No tienes cuenta? <a href="#" class="text-primary">Contacta al administrador</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .welcome-card {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
    }
    
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
        border-radius: 12px;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .stat-card {
        transition: transform 0.2s ease;
        border-radius: 10px;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    
    .progress {
        border-radius: 100px;
    }
    
    .progress-bar {
        border-radius: 100px;
    }
    
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gráfico de Rendimiento
    const performanceChart = new ApexCharts(document.querySelector("#performance-chart"), {
        chart: {
            type: 'line',
            height: '100%',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        series: [{
            name: 'Bicis Alquiladas',
            data: [120, 190, 170, 220, 250, 280, 310, 290, 330, 380, 400, 420]
        }, {
            name: 'Ingresos (€)',
            data: [12500, 18200, 15800, 19500, 22500, 24500, 26800, 25200, 28500, 31200, 33500, 35800]
        }],
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        },
        colors: ['#0d6efd', '#198754'],
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        markers: {
            size: 5,
            hover: {
                size: 7
            }
        },
        yaxis: [{
            title: {
                text: 'Bicis Alquiladas',
            },
        }, {
            opposite: true,
            title: {
                text: 'Ingresos (€)'
            }
        }],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return y.toFixed(0) + (y > 1000 ? "" : " bicis");
                    }
                    return y;
                }
            }
        }
    });
    performanceChart.render();
    
    // Gráfico de Distribución
    const distributionChart = new ApexCharts(document.querySelector("#distribution-chart"), {
        chart: {
            type: 'donut',
            height: '100%'
        },
        series: [42, 28, 30],
        labels: ['Centro', 'Norte', 'Sur'],
        colors: ['#0d6efd', '#198754', '#ffc107'],
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return '100%'
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        }
    });
    distributionChart.render();
});
</script>
@endpush

@endsection