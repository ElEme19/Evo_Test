<!-- Modal -->
<div class="modal fade" id="modalBuscarBici" tabindex="-1" aria-labelledby="buscarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Más ancho -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title " id="buscarModalLabel">Buscar Bicicleta Num. Serie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciChasis">

          <div class="mb-3 d-flex justify-content-center">
        <div class="w-50"> 
          <label for="num_chasis_buscar" class="form-label">Número de Chasis</label>
          <input type="text" id="num_chasis_buscar" name="num_chasis" class="form-control form-control-sm" required>
        </div>
      </div>

    

          <button type="submit" class="btn btn-outline-success w-10">Buscar</button>
        </form>

        <div id="resultadoBusquedaChasis" class="mt-4"></div>

        <script>
        document.getElementById('formBuscarBiciChasis').addEventListener('submit', function(e) {
          e.preventDefault();

          const numChasis = document.getElementById('num_chasis_buscar').value;
          const resultadoDiv = document.getElementById('resultadoBusquedaChasis');
          resultadoDiv.innerHTML = 'Buscando...';

          fetch("{{ route('Busquedas.busChasis') }}?num_chasis=" + encodeURIComponent(numChasis))
            .then(response => response.json())
            .then(data => {
              if (data.bici) {
                const bici = data.bici;
                resultadoDiv.innerHTML = `
                  <hr>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead class="table-light">
                        <tr>
                          <th>Num. Chasis</th>
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
                resultadoDiv.innerHTML = `<p class="text-danger">No se encontró ninguna bicicleta con ese número de chasis.</p>`;
              }
            })
            .catch(() => {
              resultadoDiv.innerHTML = `<p class="text-danger">Error al buscar la bicicleta.</p>`;
            });
        });
        </script>
      </div>
    </div>
  </div>
</div>
