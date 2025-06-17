
<?php $__env->startSection('conten'); ?>

<div class="card" style="width: 40rem;">
  <div class="card-body">
    <h5 class="card-title">Añadir Pieza</h5>
    <form method="POST" action="<?php echo e(route('piezas.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-grup">
            <label for="nombre_pieza"> Nombre:</label>
            <input type="text" id="nombre_pieza" name="nombre_pieza" class="form-control" required>
        </div>
        <div class="form-grup">
            <label for="descripcion_pieza"> Descripcion Pieza:</label>
            <input type="text" id="descripcion_pieza" name="descripcion_pieza" mt-5 class="form-control" required>
        </div>
        <button type="submit" class="form-control"  >Guardar</button>
        
    </form>
    <?php if(session('success')): ?>
    <div class="alert alert-success" role="alert" mt-5>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

  </div>
</div>


<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/piezas/crear.blade.php ENDPATH**/ ?>