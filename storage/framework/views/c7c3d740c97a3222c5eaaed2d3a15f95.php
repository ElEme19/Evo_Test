
<?php $__env->startSection('conten'); ?>

<h1>Listado de Piezas </h1>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Id Pieza</th>
      <th scope="col">Nombre</th>
      <th scope="col">Descripcion</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    
    <tr>
    <?php $__currentLoopData = $piezas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pieza): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>
    <td><?php echo e($pieza->id_piezas); ?></td>
    <td><?php echo e($pieza->nombre_pieza); ?></td>
    <td><?php echo e($pieza->descripcion_pieza); ?></td>
    <td>
      <button type="button" 
              class="btn btn-outline-success" 
              data-bs-toggle="modal" 
              data-bs-target="#editModal<?php echo e($pieza->id_piezas); ?>">
        Actualiza
      </button>
      <?php echo $__env->make('piezas.actualizar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </td>
  </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
<?php if(session('success')): ?>
    <div class="alert alert-success" role="alert" mt-5>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>


<?php $__env->stopSection(); ?> 



<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/piezas/ver.blade.php ENDPATH**/ ?>