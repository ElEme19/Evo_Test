<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evobike ~ CloudLabs @yield('title')</title>
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white !important;
        }
        
        .navbar-brand img {
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--evobike-dark) !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--evobike-primary) !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: rgba(25, 135, 84, 0.1) !important;
            color: var(--evobike-primary) !important;
        }
        
        .dropdown-item:active {
            background-color: rgba(25, 135, 84, 0.2) !important;
        }
        
        .btn-volver {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background-color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .btn-volver:hover {
            transform: translateX(-5px);
            background-color: var(--evobike-primary);
        }
        
        .btn-volver:hover img {
            filter: brightness(0) invert(1);
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
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--evobike-primary);
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn {
            transition: transform 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: scale(1.1);
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
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @php
                        $tipo = Auth::user()->user_tipo;
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/Mexico/inicio">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    
                    @if (in_array($tipo, ['0', '1', '2', '3', '5']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="bicicletaDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bicycle me-1"></i>Bicicleta
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="bicicletaDropdown">
                            <li><a class="dropdown-item" href="/Bicicleta/vista"><i class="bi bi-plus-circle me-2"></i>Nuevo</a></li>
                            <li><a class="dropdown-item" href="/ColorModelo/vista"><i class="bi bi-palette me-2"></i>Colores</a></li>
                            <li><a class="dropdown-item" href="/Lote/vista"><i class="bi bi-box-seam me-2"></i>Lote</a></li>
                            <li><a class="dropdown-item" href="/Stock/vista"><i class="bi bi-boxes me-2"></i>Stock</a></li>
                        </ul>
                    </li>
                    @endif
                    
                    @if (in_array($tipo, ['0', '2','3', '4']))
                    <li class="nav-item">
                        <a class="nav-link" href="/Envio/crear">
                            <i class="bi bi-truck me-1"></i>Envíos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Sucursal/vista">
                            <i class="bi bi-shop me-1"></i>Sucursales
                        </a>
                    </li>
                    @endif
                    
                    @if (in_array($tipo, ['0', '2']))
                    <li class="nav-item">
                        <a class="nav-link" href="/Mexico/import">
                            <i class="bi bi-upload me-1"></i>Importar
                        </a>
                    </li>
                    @endif
                    
                    @if ($tipo == '0')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i>Administración
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="/Precio/index"><i class="bi bi-tag me-2"></i>Precios</a></li>
                            <li><a class="dropdown-item" href="/Clientes/index"><i class="bi bi-people me-2"></i>Clientes</a></li>
                            <li><a class="dropdown-item" href="/Membresia"><i class="bi bi-card-checklist me-2"></i>Membresías</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-link nav-link logout-btn" type="submit" title="Cerrar sesión">
                                <i class="bi bi-power" style="font-size: 1.25rem;"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Botón de volver -->
    @php
        $actual = url()->current();
        $prev = url()->previous();
        $fallback = session('last_useful_url', route('piezas.inicio'));
        $volverA = $prev !== $actual ? $prev : $fallback;
    @endphp
    
    <a href="{{ $volverA }}" class="btn-volver" title="Volver atrás">
        <img src="{{ asset('images/arrow-left-square-fill.svg') }}" alt="Volver" width="24">
    </a>

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
                    <p class="mt-2 mb-0">© {{ date('Y') }} Evobike. Todos los derechos reservados.</p>
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
    <script>
        // Activar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Manejar dropdowns hover
            const dropdownElements = document.querySelectorAll('.dropdown');
            dropdownElements.forEach(dropdown => {
                dropdown.addEventListener('mouseenter', () => {
                    dropdown.classList.add('show');
                    dropdown.querySelector('.dropdown-menu').classList.add('show');
                });
                
                dropdown.addEventListener('mouseleave', () => {
                    dropdown.classList.remove('show');
                    dropdown.querySelector('.dropdown-menu').classList.remove('show');
                });
            });
        });
    </script>
</body>
</html>