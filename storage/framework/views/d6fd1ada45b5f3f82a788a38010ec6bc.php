<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evobike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('images/favico.ico')); ?>">

    <style>
        .navbar-nav .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .dropdown-item:active {
            background-color: rgb(185, 180, 180) !important;
            color: white !important;
        }
        .dropdown-item:hover {
            background-color: rgba(185, 180, 180);
            color: black !important;
        }
        .dropdown-item {
            color: black !important;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Mexico/inicio">
            <img src="<?php echo e(asset('images/logos.png')); ?>" alt="Evobike" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                    $tipo = Auth::user()->user_tipo;
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="/Mexico/inicio">Home</a>
                </li>

                <?php if(in_array($tipo, ['0', '1', '2', '3', '5'])): ?>
                   
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/Bicicleta/vista" id="crearDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Bicicleta
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="crearDropdown">
                            <li><a class="dropdown-item" href="/Bicicleta/vista">Nuevo</a></li>
                            <li><a class="dropdown-item" href="/ColorModelo/vista">Colores</a></li>
                            <li><a class="dropdown-item" href="/Lote/vista">Lote</a></li>
                            <li><a class="dropdown-item" href="/Stock/vista">Stock</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(in_array($tipo, ['0', '2','3', '4'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/Envio/crear">Envios</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Sucursal/vista">Sucursales</a></li>
                <?php endif; ?>

                <?php if(in_array($tipo, ['0', '2'])): ?>
                    <li class="nav-item"><a class="nav-link" href="#">Estadísticas</a></li>
                <?php endif; ?>

                <?php if($tipo == '0'): ?>
                    <li class="nav-item"><a class="nav-link" href="/Precio/index">Precio</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Clientes/index">Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Membresia">Memebresia</a></li>
                <?php endif; ?>

                <?php if(!in_array($tipo, ['0', '1', '2', '3', '4', '5'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/piezas/registrarse">Registro</a></li>
                <?php endif; ?>

                <li class="nav-item">
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="nav-link active" type="submit" style="background: none; border: none;">
                            <img src="<?php echo e(asset('images/power.svg')); ?>" alt="Cerrar sesión" style="width: 20px; height: 20px; margin-right: 5px;">
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
    $actual = url()->current();
    $prev = url()->previous();
    $fallback = session('last_useful_url', route('piezas.inicio'));
    $volverA = $prev !== $actual ? $prev : $fallback;
?>

<a href="<?php echo e($volverA); ?>" class="btn btn-outline-success p-1 ms-3 mt-2">
    <img src="<?php echo e(asset('images/arrow-left-square-fill.svg')); ?>" alt="Volver" style="width: 30px; height: 30px;">
</a>

<!-- Título -->
<h1 class="text-center my-3"><?php echo $__env->yieldContent('title'); ?></h1>

<!-- Contenido -->
<?php if(View::hasSection('conten-wrapper')): ?>
    <?php echo $__env->yieldContent('conten-wrapper'); ?>
<?php else: ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="card shadow p-4 w-100" style="max-width: 800px;">
            <?php echo $__env->yieldContent('conten'); ?>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/layout/app.blade.php ENDPATH**/ ?>