
<div class="modal fade" id="editModal<?php echo e($pieza->id_piezas); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($pieza->id_piezas); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?php echo e($pieza->id_piezas); ?>">Actualiza</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm<?php echo e($pieza->id_piezas); ?>" method="POST" action="<?php echo e(route('piezas.update', $pieza)); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="mb-3">
            <label for="nombre_pieza">Nombre:</label>
            <input type="text" id="nombre_pieza" name="nombre_pieza" value="<?php echo e($pieza->nombre_pieza); ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="descripcion_pieza">Descripción Pieza:</label>
            <input type="text" id="descripcion_pieza" name="descripcion_pieza" value="<?php echo e($pieza->descripcion_pieza); ?>" class="form-control" required>
          </div>

          
          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal<?php echo e($pieza->id_piezas); ?>" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="confirmModal<?php echo e($pieza->id_piezas); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($pieza->id_piezas); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel<?php echo e($pieza->id_piezas); ?>">¿Seguro que estás seguro?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará la pieza. ¿Estás seguro de continuar?
      </div>
      <div class="modal-footer">
     
        <button class="btn btn-danger" data-bs-target="#editModal<?php echo e($pieza->id_piezas); ?>" data-bs-toggle="modal">
          Volver
        </button>
       
        <button class="btn btn-success" onclick="document.getElementById('updateForm<?php echo e($pieza->id_piezas); ?>').submit();">
          Sí, actualizar
        </button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/piezas/actualizar.blade.php ENDPATH**/ ?>