<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar<?php echo e($c->id_cliente); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($c->id_cliente); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?php echo e($c->id_cliente); ?>">Actualizar Cliente</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm<?php echo e($c->id_cliente); ?>" method="POST" action="<?php echo e(route('Clientes.update', $c->id_cliente)); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <input type="hidden" name="id_cliente" value="<?php echo e($c->id_cliente); ?>">

          <div class="mb-3">
            <label>ID Cliente:</label>
            <p><?php echo e($c->id_cliente); ?></p>
          </div>

         <div class="mb-3">
  <label for="id_membresia<?php echo e($c->id_cliente); ?>">Seleccionar Membresía:</label>
  <select name="id_membresia" id="id_membresia<?php echo e($c->id_cliente); ?>" class="form-select" required>
    <option value="" disabled>Seleccione una membresía</option>
    <?php $__currentLoopData = $membresias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($m->id_membresia); ?>" <?php echo e($c->id_membresia == $m->id_membresia ? 'selected' : ''); ?>>
        <?php echo e($m->descripcion_general); ?>

      </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
</div>



          <div class="mb-3">
            <label for="nombre<?php echo e($c->id_cliente); ?>">Nombre:</label>
            <input type="text" id="nombre<?php echo e($c->id_cliente); ?>" name="nombre" value="<?php echo e($c->nombre); ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="apellido<?php echo e($c->id_cliente); ?>">Apellido:</label>
            <input type="text" id="apellido<?php echo e($c->id_cliente); ?>" name="apellido" value="<?php echo e($c->apellido); ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="correo<?php echo e($c->id_cliente); ?>">Correo Electrónico:</label>
            <input type="email" id="correo<?php echo e($c->id_cliente); ?>" name="correo" value="<?php echo e($c->correo); ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="telefono<?php echo e($c->id_cliente); ?>">Teléfono:</label>
            <input type="text" id="telefono<?php echo e($c->id_cliente); ?>" name="telefono" value="<?php echo e($c->telefono); ?>" class="form-control" required>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal<?php echo e($c->id_cliente); ?>" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal<?php echo e($c->id_cliente); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo e($c->id_cliente); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel<?php echo e($c->id_cliente); ?>">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la información del cliente. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar<?php echo e($c->id_cliente); ?>" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm<?php echo e($c->id_cliente); ?>').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Clientes/update.blade.php ENDPATH**/ ?>