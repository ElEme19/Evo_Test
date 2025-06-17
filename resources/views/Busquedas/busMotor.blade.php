<!-- Modal -->
<div class="modal fade" id="modalBuscarBiciMotor" tabindex="-1" aria-labelledby="buscarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para más espacio -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buscarModalLabel">Buscar Bicicleta por Motor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciMotor">
          <div class="mb-3 d-flex justify-content-center">
            <div class="w-50">
              <label for="num_motor_buscar" class="form-label">Número de Motor</label>
              <input type="text" id="num_motor_buscar" name="num_motor" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-outline-success btn-sm">Buscar</button>
          </div>
        </form>

        <script>
        document.getElementById('formBuscarBiciMotor').addEventListener('submit', function(e) {
          e.preventDefault();

          const numMotor = document.getElementById('num_motor_buscar').value;
          const resultadoDiv = document.getElementById('resultadoBusquedaM');

          resultadoDiv.innerHTML = 'Buscando...';

          fetch("{{ route('Busquedas.busMotor') }}?num_motor=" + encodeURIComponent(numMotor))
            .then(response => response.json())
            .then(data => {
              if (data.bici) {
                const bici = data.bici;
                resultadoDiv.innerHTML = `
                  <div class="table-responsive mt-4">
                    <table class="table table-striped table-bordered table-sm">
                      <thead class="table-light">
                        <tr>
                          <th>Núm. Serie</th>
                          <th>Motor</th>
                          <th>Modelo</th>
                          <th>Color</th>
                          <th>Voltaje</th>
                          <th>Stock</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>${bici.num_chasis}</td>
                          <td>${bici.num_motor || 'N/A'}</td>
                          <td>${bici.modelo?.nombre_modelo || 'N/A'}</td>
                          <td>${bici.color?.nombre_color || 'N/A'}</td>
                          <td>${bici.voltaje || 'N/A'}</td>
                          <td>${bici.tipo_stock?.nombre_stock || 'N/A'}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                `;
              } else {
                resultadoDiv.innerHTML = `<p class="text-danger mt-3">No se encontró ninguna bicicleta con ese número de motor.</p>`;
              }
            })
            .catch(() => {
              resultadoDiv.innerHTML = `<p class="text-danger mt-3">Error al buscar la bicicleta. <strong>==> Llamar al Inge.</strong></p>`;
            });
        });
        </script>

        <div id="resultadoBusquedaM" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>
