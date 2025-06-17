<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar<?php echo e($tipo->id_tipoStock); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($tipo->id_tipoStock); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?php echo e($tipo->id_tipoStock); ?>">Actualizar Tipo de Stock</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm<?php echo e($tipo->id_tipoStock); ?>" method="POST" action="<?php echo e(route('Stock.update', $tipo->id_tipoStock)); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="mb-3">
            <input type="hidden" name="id_tipoStock" value="<?php echo e($tipo->id_tipoStock); ?>">
            <label>ID Tipo Stock:</label>
            <p><?php echo e($tipo->id_tipoStock); ?></p>
          </div>

          <div class="mb-3">
            <label for="nombre_stock">Descripción:</label>
            <input type="text" id="nombre_stock" name="nombre_stock" value="<?php echo e($tipo->nombre_stock); ?>" class="form-control" required>
          </div>

          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal<?php echo e($tipo->id_tipoStock); ?>" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal<?php echo e($tipo->id_tipoStock); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo e($tipo->id_tipoStock); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel<?php echo e($tipo->id_tipoStock); ?>">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la descripción del tipo de stock. ¿Estás segure de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar<?php echo e($tipo->id_tipoStock); ?>" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm<?php echo e($tipo->id_tipoStock); ?>').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Stock/actualizar.blade.php ENDPATH**/ ?>