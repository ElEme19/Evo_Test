<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('Evobike ~ CloudLabs') @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favico.ico') }}">
    
    <style>
        :root {
            --evobike-primary: #198754;
            --evobike-secondary: #6c757d;
            --evobike-light: #f8f9fa;
            --evobike-dark: #212529;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: white !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--evobike-dark) !important;
            padding: 0.5rem 1rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--evobike-primary) !important;
        }
        
        .dropdown-menu {
            border: none;
        }
        
        .dropdown-item:hover {
            background-color: rgba(25, 135, 84, 0.1) !important;
            color: var(--evobike-primary) !important;
        }
        
        .main-container {
            min-height: calc(100vh - 120px);
            padding-top: 20px;
            padding-bottom: 40px;
        }
        
        .page-title {
            color: var(--evobike-primary);
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/Mexico/inicio">
                <img src="{{ asset('images/logos.png') }}" alt="Evobike" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="@lang('Toggle navigation')">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @php
                        $tipo = Auth::user()->user_tipo;
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/Mexico/inicio">
                            <i class="bi bi-house-door me-1"></i>@lang('Inicio')
                        </a>
                    </li>
                    
                    @if (in_array($tipo, ['0', '1', '2', '3', '5']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="bicicletaDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bicycle me-1"></i>@lang('Bicicleta')
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="bicicletaDropdown">
                            <li><a class="dropdown-item" href="/Bicicleta/vista"><i class="bi bi-plus-circle me-2"></i>@lang('Nuevo')</a></li>
                            <li><a class="dropdown-item" href="/Modelo/ver"><i class="bi bi-bicycle me-2"></i>@lang('Modelos')</a></li>
                            <li><a class="dropdown-item" href="/ColorModelo/vista"><i class="bi bi-palette me-2"></i>@lang('Colores')</a></li>
                            <li><a class="dropdown-item" href="/Lote/vista"><i class="bi bi-box-seam me-2"></i>@lang('Lote')</a></li>
                            <li><a class="dropdown-item" href="/Stock/vista"><i class="bi bi-boxes me-2"></i>@lang('Stock')</a></li>
                        </ul>
                    </li>
                    @endif
                    
                    @if (in_array($tipo, ['0', '2','3', '4']))
                    <li class="nav-item">
                        <a class="nav-link" href="/pedido/ver">
                            <i class="bi bi-truck me-1"></i>@lang('Pedidos')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Sucursal/vista">
                            <i class="bi bi-shop me-1"></i>@lang('Sucursales')
                        </a>
                    </li>
                    @endif

@if (in_array($tipo, ['0', '2', '3', '4']))
    <li class="nav-item">
        <a class="nav-link" href="/area/ver">
            <i class="bi bi-diagram-3 me-1"></i>@lang('Áreas')
        </a>
    </li>
@endif

                    @if (in_array($tipo, ['0', '2']))
                    <li class="nav-item">
                        <a class="nav-link" href="/Mexico/import">
                            <i class="bi bi-upload me-1"></i>@lang('Importar')
                        </a>
                    </li>
                    @endif
                    
                    @if ($tipo == '0')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i>@lang('Administración')
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="/Precio/index"><i class="bi bi-tag me-2"></i>@lang('Precios')</a></li>
                            <li><a class="dropdown-item" href="/Clientes/index"><i class="bi bi-people me-2"></i>@lang('Clientes')</a></li>
                            <li><a class="dropdown-item" href="/Membresia/index"><i class="bi bi-card-checklist me-2"></i>@lang('Membresías')</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit" title="@lang('Cerrar sesión')">
                                <i class="bi bi-power" style="font-size: 1.25rem;"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="main-container">
        <div class="container">
            @hasSection('title')
                <h1 class="text-center page-title">
                    @yield('title')
                </h1>
            @endif
            
            @if (View::hasSection('conten-wrapper'))
                @yield('conten-wrapper')
            @else
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card bg-white">
                            <div class="card-body">
                                @yield('conten')
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <img src="{{ asset('images/logos.png') }}" alt="Evobike" height="30">
                    <p class="mt-2 mb-0">© {{ date('Y') }} CloudLabs. @lang('Todos los derechos reservados.')</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <i class="bi bi-envelope me-2"></i>contacto@evobike.com<br>
                        <i class="bi bi-telephone me-2"></i>+52 55 1234 5678
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>


        <!-- Scripts base -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery necesario para ApexCharts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Aquí se insertarán los scripts adicionales -->
    @stack('scripts')

</body>
</html>

