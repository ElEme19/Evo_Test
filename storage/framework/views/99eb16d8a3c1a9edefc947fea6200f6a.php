
<?php $__env->startSection('conten'); ?>

<?php
    $user = Auth::guard('usuarios')->user();
?>

<div class="container">
    <?php if($user): ?>
        <h1 class="mt-5 text-center"><?php echo e($user->tipo_dia); ?>!, <?php echo e($user->user_name); ?>  </h1>
        

        <div class="row justify-content-center mt-5">
            <div class="col-mb-8">
                <div class="alert alert-info text-center">
                    ¡Bienvenide a Evobike!
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-mb-8">
                <div class="alert alert-info text-center">
                    Tipo de usuario: <?php echo e($user->tipo_texto); ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <p>No has iniciado sesión.</p>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Mexico/inicio.blade.php ENDPATH**/ ?>