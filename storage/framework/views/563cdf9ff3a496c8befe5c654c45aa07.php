<!-- Botón que abre el modal -->
<button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalBuscarBici">
  Buscar
</button>

<!-- Modal único -->
<div class="modal fade <?php if(request()->has('num_chasis')): ?> show <?php endif; ?>" 
     id="modalBuscarBici" 
     tabindex="-1" 
     aria-labelledby="buscarModalLabel" 
     aria-hidden="<?php echo e(request()->has('num_chasis') ? 'false' : 'true'); ?>" 
     style="<?php echo e(request()->has('num_chasis') ? 'display: block;' : ''); ?>">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="buscarModalLabel">
          <?php echo e(isset($bici) ? 'Resultado de la búsqueda' : 'Buscar Bicicleta por Chasis'); ?>

        </h1>
        <a href="<?php echo e(url()->current()); ?>" class="btn-close" aria-label="Cerrar"></a>
      </div>
      <div class="modal-body">
        <?php if(!isset($bici)): ?>
          <!-- Mostrar formulario de búsqueda -->
          <form method="GET" action="<?php echo e(route('bicicletas.buscar')); ?>">
            <div class="mb-3">
              <label for="num_chasis_buscar" class="form-label">Número de Chasis</label>
              <input type="text" id="num_chasis_buscar" name="num_chasis" class="form-control" required value="<?php echo e(request('num_chasis')); ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
          </form>
        <?php else: ?>
          <!-- Mostrar resultados dentro del mismo modal -->
          <p><strong>Chasis:</strong> <?php echo e($bici->num_chasis); ?></p>
          <p><strong>Motor:</strong> <?php echo e($bici->num_motor ?? 'N/A'); ?></p>
          <p><strong>Modelo:</strong> <?php echo e($bici->modelo->nombre_modelo ?? 'N/A'); ?></p>
          <p><strong>Color:</strong> <?php echo e($bici->color->nombre_color ?? 'N/A'); ?></p>
          <p><strong>Voltaje:</strong> <?php echo e($bici->voltaje ?? 'N/A'); ?></p>
          <p><strong>Stock:</strong> <?php echo e($bici->tipoStock->nombre_stock ?? 'N/A'); ?></p>

          <a href="<?php echo e(url()->current()); ?>" class="btn btn-secondary mt-3 w-100">Buscar otra bici</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/bicicleta/actualizar.blade.php ENDPATH**/ ?>