<!-- Modal de edición -->
<div class="modal fade" id="modalActualizar<?php echo e($lote->id_lote); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel<?php echo e($lote->id_lote); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?php echo e($lote->id_lote); ?>">Actualizar Lote</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm<?php echo e($lote->id_lote); ?>" method="POST" action="<?php echo e(route('Lote.update', $lote->id_lote)); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

           <div class="mb-3">
            <input type="hidden" name="id_lote" value="<?php echo e($lote->id_lote); ?>">
            <label>ID lote:</label>
            <p> <?php echo e($lote->id_lote); ?></p>
           
          </div>

          <div class="mb-3">
    <label for="fecha_produccion">Fecha de Producción:</label>
    <input type="date" id="fecha_produccion" name="fecha_produccion" value="<?php echo e(\Carbon\Carbon::parse($lote->fecha_produccion)->format('Y-m-d')); ?>" class="form-control" required>
    </div>


          <button type="button" class="btn btn-success form-control" data-bs-target="#confirmModal<?php echo e($lote->id_lote); ?>" data-bs-toggle="modal">
            Actualizar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal<?php echo e($lote->id_lote); ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo e($lote->id_lote); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel<?php echo e($lote->id_lote); ?>">¿Confirmar actualización?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción actualizará lafecha del Lote. ¿Estás segure de continuar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-bs-target="#modalActualizar<?php echo e($lote->id_lote); ?>" data-bs-toggle="modal">Volver</button>
        <button class="btn btn-success" onclick="document.getElementById('updateForm<?php echo e($lote->id_lote); ?>').submit();">Sí, actualizar</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Lote/actualizar.blade.php ENDPATH**/ ?>