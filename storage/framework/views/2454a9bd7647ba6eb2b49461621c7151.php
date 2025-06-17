
<!-- Modal -->
<div class="modal fade" id="modalBuscarBici" tabindex="-1" aria-labelledby="buscarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buscarModalLabel">Buscar Bicicleta por Chasis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciChasis">
          <div class="mb-3">
            <label for="num_chasis_buscar" class="form-label">Número de Chasis</label>
            <input type="text" id="num_chasis_buscar" name="num_chasis" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-outline-success w-100">Buscar</button>
        </form>

        
    <script>
    document.getElementById('formBuscarBiciChasis').addEventListener('submit', function(e) {
      e.preventDefault(); // Evitar que se envíe el formulario normalmente

      const numChasis = document.getElementById('num_chasis_buscar').value;
      const resultadoDiv = document.getElementById('resultadoBusquedaChasis');

      // Limpiar resultados previos
      resultadoDiv.innerHTML = 'Buscando...';

      fetch("<?php echo e(route('bicicletas.buscar')); ?>?num_chasis=" + encodeURIComponent(numChasis))
        .then(response => response.json())
        .then(data => {
          if (data.bici) {
            const bici = data.bici;
            resultadoDiv.innerHTML = `
              <hr>
              <p><strong>Num. Serie:</strong> ${bici.num_chasis}</p>
              <p><strong>Motor:</strong> ${bici.num_motor || 'N/A'}</p>
              <p><strong>Modelo:</strong> ${bici.modelo?.nombre_modelo || 'N/A'}</p>
              <p><strong>Color:</strong> ${bici.color?.nombre_color || 'N/A'}</p>
              <p><strong>Voltaje:</strong> ${bici.voltaje || 'N/A'}</p>
              <p><strong>Stock:</strong> ${bici.tipo_stock?.nombre_stock || 'N/A'}</p>
            `;
          } else {
            resultadoDiv.innerHTML = `<p class="text-danger">No se encontró ninguna bicicleta con ese número de chasis.</p>`;
          }
        })
        .catch(() => {
          resultadoDiv.innerHTML = `<p class="text-danger">Error al buscar la bicicleta.</p>`;
        });
    });
    </script>

        <!-- Aquí aparecerá el resultado -->
        <div id="resultadoBusquedaChasis" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Bicicleta/actualizar.blade.php ENDPATH**/ ?>