<!-- Modal -->
<?php if(isset($bici) && $bici): ?>
<div class="modal fade show" id="modalInfoBici" tabindex="-1" aria-labelledby="modalInfoBiciLabel" style="display:block;" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalInfoBiciLabel">Información de la bicicleta</h5>
        <a href="<?php echo e(url()->current()); ?>" class="btn-close" aria-label="Cerrar"></a>
      </div>
      <div class="modal-body">
        <p><strong>Chasis:</strong> <?php echo e($bici->num_chasis); ?></p>
        <p><strong>Motor:</strong> <?php echo e($bici->num_motor ?? 'N/A'); ?></p>
        <p><strong>Modelo:</strong> <?php echo e($bici->modelo->nombre_modelo ?? 'N/A'); ?></p>
        <p><strong>Color:</strong> <?php echo e($bici->color->nombre_color ?? 'N/A'); ?></p>
        <p><strong>Voltaje:</strong> <?php echo e($bici->voltaje ?? 'N/A'); ?></p>
        <p><strong>Stock:</strong> <?php echo e($bici->tipoStock->nombre_stock ?? 'N/A'); ?></p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-secondary">Cerrar</a>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop fade show"></div>
<?php endif; ?>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/bicicleta/busca.blade.php ENDPATH**/ ?>