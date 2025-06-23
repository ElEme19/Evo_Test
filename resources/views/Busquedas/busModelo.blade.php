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

        <div id="resultadoBusquedaModelo" class="mt-4" style="overflow-x: auto;"></div>
      </div>
    </div>
  </div>
</div>

<script>
  // Variables globales para la paginación
  let currentBicis = [];
  let currentPage = 1;
  const itemsPerPage = 10;

  // Función para poblar el select de modelos
  async function fetchModelos() {
    try {
      const response = await fetch("{{ route('Busquedas.busModelo') }}");
      if (!response.ok) throw new Error('Error al cargar modelos');
      
      const data = await response.json();
      const select = document.getElementById('modelo_buscar');
      select.innerHTML = '<option value="" disabled selected>Elige modelo</option>';
      
      (data.modelos || []).forEach(mod => {
        const option = document.createElement('option');
        option.value = mod.id_modelo;
        option.textContent = mod.nombre_modelo;
        select.appendChild(option);
      });
    } catch (error) {
      console.error('Error:', error);
      alert('Error al cargar los modelos');
    }
  }

  // Función para mostrar los datos paginados
  function displayPaginatedData(page = 1) {
    currentPage = page;
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedItems = currentBicis.slice(startIndex, endIndex);
    const totalPages = Math.ceil(currentBicis.length / itemsPerPage);
    
    const resultadoDiv = document.getElementById('resultadoBusquedaModelo');
    
    if (paginatedItems.length > 0) {
      let html = `
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
          <table class="table table-striped table-bordered mb-2">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
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

      paginatedItems.forEach(b => {
        html += `
          <tr>
            <td>${b.num_chasis || 'N/A'}</td>
            <td>${b.num_motor || 'N/A'}</td>
            <td>${b.modelo?.nombre_modelo || 'N/A'}</td>
            <td>${b.color?.nombre_color || 'N/A'}</td>
            <td>${b.voltaje || 'N/A'}</td>
            <td>${b.tipo_stock?.nombre_stock || 'N/A'}</td>
          </tr>
        `;
      });

      html += `</tbody></table></div>`;

      // Paginación ajustada al modal
      html += `
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2 gap-2">
          <div class="text-muted small">
            Mostrando ${startIndex + 1}-${Math.min(endIndex, currentBicis.length)} de ${currentBicis.length} registros
          </div>
          
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm flex-wrap justify-content-center mb-0">
              <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="displayPaginatedData(${currentPage - 1})" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
      `;

      // Mostrar números de página (máximo 5 páginas visibles)
      const maxVisiblePages = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
      
      if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }

      if (startPage > 1) {
        html += `
          <li class="page-item">
            <a class="page-link" href="#" onclick="displayPaginatedData(1)">1</a>
          </li>
          ${startPage > 2 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
        `;
      }

      for (let i = startPage; i <= endPage; i++) {
        html += `
          <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="displayPaginatedData(${i})">${i}</a>
          </li>
        `;
      }

      if (endPage < totalPages) {
        html += `
          ${endPage < totalPages - 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
          <li class="page-item">
            <a class="page-link" href="#" onclick="displayPaginatedData(${totalPages})">${totalPages}</a>
          </li>
        `;
      }

      html += `
              <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="displayPaginatedData(${currentPage + 1})" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      `;

      resultadoDiv.innerHTML = html;
    } else {
      resultadoDiv.innerHTML = '<p class="text-danger">No se encontraron bicicletas para el modelo seleccionado.</p>';
    }
  }

  // Event listeners
  document.getElementById('modalBuscarBiciModelo').addEventListener('show.bs.modal', fetchModelos);

  document.getElementById('formBuscarBiciModelo').addEventListener('submit', async function(e) {
    e.preventDefault();
    const idModelo = document.getElementById('modelo_buscar').value;
    const resultadoDiv = document.getElementById('resultadoBusquedaModelo');
    
    if (!idModelo) return;
    
    resultadoDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';

    try {
      const response = await fetch(`{{ route('Busquedas.busModelo') }}?modelo=${encodeURIComponent(idModelo)}`);
      if (!response.ok) throw new Error('Error en la búsqueda');
      
      const data = await response.json();
      currentBicis = data.bicis || [];
      currentPage = 1;
      
      displayPaginatedData();
    } catch (error) {
      console.error('Error:', error);
      resultadoDiv.innerHTML = '<p class="text-danger">Error al cargar los resultados. Intente nuevamente.</p>';
    }
  });
</script>