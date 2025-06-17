<!-- Modal -->
<div class="modal fade" id="modalBuscarBiciMotor" tabindex="-1" aria-labelledby="buscarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buscarModalLabel">Buscar Bicicleta por Motor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciMotor">
          <div class="mb-3">
            <label for="num_motor_buscar" class="form-label">Número de Motor</label>
            <input type="text" id="num_motor_buscar" name="num_motor" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-outline-success w-100">Buscar</button>
        </form>

        <script>
        document.getElementById('formBuscarBiciMotor').addEventListener('submit', function(e) {
          e.preventDefault(); // Evitar envío normal del formulario

          const numMotor = document.getElementById('num_motor_buscar').value;
          const resultadoDiv = document.getElementById('resultadoBusquedaM');

          resultadoDiv.innerHTML = 'Buscando...';

          fetch("<?php echo e(route('bicicletas.busMotor')); ?>?num_motor=" + encodeURIComponent(numMotor))
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
                resultadoDiv.innerHTML = `<p class="text-danger">No se encontró ninguna bicicleta con ese número de motor.</p>`;
              }
            })
            .catch(() => {
              resultadoDiv.innerHTML = `<p class="text-danger">Error al buscar la bicicleta  ==> Llamar al Inge.</p>`;
            });
        });
        </script>

        <div id="resultadoBusquedaM" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Bicicleta/busMotor.blade.php ENDPATH**/ ?>