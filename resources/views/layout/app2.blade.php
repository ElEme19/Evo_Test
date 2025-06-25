<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CloudLabs - Inicio de Sesión</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/CloudLabs.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4DB53F;
            --dark-bg: #0D1117;
            --card-radius: 1.25rem;
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: var(--dark-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .login-card {
            border-radius: var(--card-radius);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform var(--transition-speed) ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .login-image {
            height: 100%;
            object-fit: cover;
            border-radius: var(--card-radius) 0 0 var(--card-radius);
        }
        
        .login-content {
            padding: 3rem;
        }
        
        @media (max-width: 768px) {
            .login-image {
                border-radius: var(--card-radius) var(--card-radius) 0 0;
                height: 200px;
            }
            
            .login-content {
                padding: 2rem;
            }
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(77, 181, 63, 0.25);
        }
    </style>
</head>

<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="login-card card overflow-hidden">
                        <div class="row g-0">
                            <!-- Imagen de fondo -->
                            <div class="col-lg-5 d-none d-lg-flex">
                                <img src="{{ asset('images/bici_Evobike.png') }}" 
                                     alt="Login visual" 
                                     class="login-image img-fluid">
                            </div>
                            
                            <!-- Contenido del formulario -->
                            <div class="col-lg-7 d-flex align-items-center">
                                <div class="login-content card-body p-lg-5">
                                    @yield('fondo')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>