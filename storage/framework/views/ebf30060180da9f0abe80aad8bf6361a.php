<!-- Modal: Buscar por Modelo -->
<div class="modal fade" id="modalBuscarBiciModelo" tabindex="-1" aria-labelledby="buscarModeloLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buscarModeloLabel">Buscar Bicicletas por Modelo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciModelo">
          <div class="mb-3">
            <label for="modelo_buscar" class="form-label">Selecciona un Modelo</label>
            <select id="modelo_buscar" name="modelo" class="form-select" required>
              <option value="" disabled selected>-- Elige un modelo --</option>
              <?php $__currentLoopData = $modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($mod->nombre_modelo); ?>"><?php echo e($mod->nombre_modelo); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <button type="submit" class="btn btn-outline-info w-100">Buscar</button>
        </form>

        <div id="resultadoBusquedaModelo" class="mt-4"></div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('formBuscarBiciModelo').addEventListener('submit', function(e) {
  e.preventDefault();

  // Tomamos el texto del modelo seleccionado
  const nombreModelo = document.getElementById('modelo_buscar').value;
  const resultadoDiv = document.getElementById('resultadoBusquedaModelo');
  resultadoDiv.innerHTML = `<div class="text-center py-3"><em>Buscando…</em></div>`;

  fetch("<?php echo e(route('bicicletas.busModelo')); ?>?modelo=" + encodeURIComponent(nombreModelo))
    .then(r => r.json())
    .then(data => {
      const bicis = data.bicis || [];
      if (bicis.length > 0) {
        let html = `
          <table class="table table-striped table-bordered">
            <thead class="table-light">
              <tr>
                <th>Num. Serie</th>
                <th>Motor</th>
                <th>Modelo</th>
                <th>Color</th>
                <th>Voltaje</th>
                <th>Stock</th>
              </tr>
            </thead>
            <tbody>
        `;
        bicis.forEach(b => {
          html += `
            <tr>
              <td>${b.num_chasis}</td>
              <td>${b.num_motor || 'N/A'}</td>
              <td>${b.modelo?.nombre_modelo || 'N/A'}</td>
              <td>${b.color?.nombre_color || 'N/A'}</td>
              <td>${b.voltaje || 'N/A'}</td>
              <td>${b.tipo_stock?.nombre_stock || 'N/A'}</td>
            </tr>
          `;
        });
        html += `</tbody></table>`;
        resultadoDiv.innerHTML = html;
      } else {
        resultadoDiv.innerHTML = `<p class="text-danger">No se encontraron bicicletas para el modelo seleccionado.</p>`;
      }
    })
    .catch(err => {
      console.error('Error en fetch:', err);
      resultadoDiv.innerHTML = `<p class="text-danger">Error al buscar bicicletas por modelo.</p>`;
    });
});
</script><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Bicicleta/busMOdelo.blade.php ENDPATH**/ ?>