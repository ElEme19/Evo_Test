<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar<?php echo e($color->id_colorM); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($color->id_colorM); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?php echo e($color->id_colorM); ?>">Actualizar Color del Modelo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm<?php echo e($color->id_colorM); ?>" method="POST" action="<?php echo e(route('Color.update', $color->id_colorM)); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

           <div class="mb-3">
            <input type="hidden" name="id_colorM" value="<?php echo e($color->id_colorM); ?>">
            <label>ID Color:</label>
            <p> <?php echo e($color->id_colorM); ?></p>
           
          </div>

          

                <div class="mb-3">
                <label for="id_modelo" class="form-label">ID Modelo</label>
                <select class="form-select" name="id_modelo" id="id_modelo" required>
              <option value="">Selecciona un modelo</option>
              <?php $__currentLoopData = $modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($modelo->id_modelo); ?>" 
                      <?php if($modelo->id_modelo == $color->id_modelo): ?> selected <?php endif; ?>>
                      <?php echo e($modelo->nombre_modelo ?? 'Sin nombre'); ?>

                  </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <div class="invalid-feedback">Seleccione un modelo válido.</div>
          </div>




          <div class="mb-3">
            <label for="nombre_color">Nombre del Color:</label>
            <input type="text" id="nombre_color" name="nombre_color" value="<?php echo e($color->nombre_color); ?>" class="form-control" required>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal<?php echo e($color->id_colorM); ?>" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal<?php echo e($color->id_colorM); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo e($color->id_colorM); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel<?php echo e($color->id_colorM); ?>">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará el color del modelo. ¿Estás segure de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar<?php echo e($color->id_colorM); ?>" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm<?php echo e($color->id_colorM); ?>').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/ColorModelo/actualizar.blade.php ENDPATH**/ ?>