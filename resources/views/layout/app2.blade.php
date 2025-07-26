<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CloudLabs</title>
   <link rel="icon" type="image/png" href="{{ asset('images/favico.ico') }}?v=3">
    
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
                                <img src="{{ asset('images/urban.png') }}" 
                                     alt="Login visual" 
                                     class="login-image img-fluid">
                            </div>
                            
                            <!-- Contenido del formulario -->
                            <div class="col-lg-7 d-flex align-items-center">
                                <div class="login-content card-body p-lg-5">
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