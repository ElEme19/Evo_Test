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
          <div class="mb-3 d-flex justify-content-center">
        <div class="w-50"> 
            <label for="modelo_buscar" class="form-label">Selecciona un Modelo</label>
            <select id="modelo_buscar" name="modelo" class="form-select" required>
              <option value="" disabled selected>Elige un modelo</option>
            </select>
          </div>
          </div>
          <button type="submit" class="btn btn-outline-success w-10">Buscar</button>
        </form>

        <div id="resultadoBusquedaModelo" class="mt-4"></div>
      </div>
    </div>
  </div>
</div>

<script>
  // Función para poblar el select y buscar bicicletas según parámetro   -->  BTW Lo hizo jaime
  async function fetchModelos() {
    const response = await fetch("<?php echo e(route('Busquedas.busModelo')); ?>");
    const data = await response.json();
    const select = document.getElementById('modelo_buscar');
    select.innerHTML = '<option value="" disabled selected> Elige modelo </option>';
    (data.modelos || []).forEach(mod => {
      const option = document.createElement('option');
      option.value = mod.id_modelo;
      option.textContent = mod.nombre_modelo;
      select.appendChild(option);
    });
  }

  document.getElementById('modalBuscarBiciModelo')
    .addEventListener('show.bs.modal', fetchModelos);

  document.getElementById('formBuscarBiciModelo')
    .addEventListener('submit', async function (e) {
      e.preventDefault();
      const idModelo = document.getElementById('modelo_buscar').value;
      const resultadoDiv = document.getElementById('resultadoBusquedaModelo');
      resultadoDiv.innerHTML = '<div class="text-center py-3"><em>Buscando…</em></div>';

      const response = await fetch("<?php echo e(route('Busquedas.busModelo')); ?>?modelo=" + encodeURIComponent(idModelo));
      const data = await response.json();
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
        html += '</tbody></table>';
        resultadoDiv.innerHTML = html;
      } else {
        resultadoDiv.innerHTML = '<p class="text-danger">No se encontraron bicicletas para el modelo seleccionado.</p>';
      }
    });
</script><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Busquedas/busModelo.blade.php ENDPATH**/ ?>