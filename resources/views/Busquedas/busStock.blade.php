<style>
  .pagination .page-link {
    color: #198754; /* Verde Bootstrap */
    background-color: #f8f9fa; /* Fondo claro */
    border-color: #dee2e6;
    transition: all 0.2s ease-in-out;
    margin: 0 2px;
    min-width: 38px;
    text-align: center;
  }

  .pagination .page-link:hover {
    background-color: #198754;
    color: #fff;
  }

  .pagination .page-item.active .page-link {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
  }

  .pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #e9ecef;
    border-color: #dee2e6;
  }

  /* Estilos para el contenedor de paginación */
  .pagination-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
  }

  .pagination-info {
    font-size: 0.9rem;
    color: #6c757d;
  }

  /* Ajustes para la tabla */
  .table-container {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 15px;
  }

  .table-container thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
  }
</style>

<!-- Modal: Buscar por Stock -->
<div class="modal fade" id="modalBuscarBiciStock" tabindex="-1" aria-labelledby="buscarStockLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buscarStockLabel">Buscar Bicicletas por Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscarBiciStock">
          <div class="mb-3 d-flex justify-content-center">
            <div class="w-50">
              <label for="stock_buscar" class="form-label">Selecciona un Tipo de Stock</label>
              <select id="stock_buscar" name="stock" class="form-select" required>
                <option value="" disabled selected>Elige un stock</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-outline-success w-10">Buscar</button>
        </form>

        <div id="resultadoBusquedaStock" class="mt-4"></div>
        <div id="paginacionStock" class="pagination-container"></div>
      </div>
    </div>
  </div>
</div>

<script>
  // Variables para controlar la paginación
  let currentStockPage = 1;
  let totalStockPages = 1;
  let currentStockId = null;

  // Cargar tipos de stock cuando se abra el modal
  async function fetchStocks() {
    try {
      const response = await fetch("{{ route('Busquedas.busStock') }}");
      const data = await response.json();
      const select = document.getElementById('stock_buscar');
      select.innerHTML = '<option value="" disabled selected>Elige Stock</option>';
      (data.stocks || []).forEach(stock => {
        const option = document.createElement('option');
        option.value = stock.id_tipoStock;
        option.textContent = stock.nombre_stock;
        select.appendChild(option);
      });
      
      // Limpiar resultados previos
      document.getElementById('resultadoBusquedaStock').innerHTML = '';
      document.getElementById('paginacionStock').innerHTML = '';
    } catch (error) {
      console.error('Error al cargar stocks:', error);
    }
  }

  document.getElementById('modalBuscarBiciStock')
    .addEventListener('show.bs.modal', fetchStocks);

  // Función para generar los botones de paginación
  function generatePagination(current, last) {
    let paginationHtml = '';
    const maxVisiblePages = 5; // Máximo número de páginas visibles
    
    // Mostrar información de paginación
    paginationHtml += `
      <div class="pagination-info">
        Página ${current} de ${last}
      </div>
      <nav aria-label="Paginación">
        <ul class="pagination pagination-sm flex-wrap justify-content-center">
    `;
    
    // Botón Anterior
    paginationHtml += `
      <li class="page-item ${current === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${current - 1}" aria-label="Anterior">
          &laquo;
        </a>
      </li>
    `;
    
    // Mostrar páginas cercanas a la actual
    let startPage = Math.max(1, current - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(last, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Mostrar primera página si no está visible
    if (startPage > 1) {
      paginationHtml += `
        <li class="page-item">
          <a class="page-link" href="#" data-page="1">1</a>
        </li>
        ${startPage > 2 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
      `;
    }
    
    // Páginas numeradas
    for (let p = startPage; p <= endPage; p++) {
      paginationHtml += `
        <li class="page-item ${p === current ? 'active' : ''}">
          <a class="page-link" href="#" data-page="${p}">${p}</a>
        </li>
      `;
    }
    
    // Mostrar última página si no está visible
    if (endPage < last) {
      paginationHtml += `
        ${endPage < last - 1 ? '<li class="page-item disabled"><span class="page-link">...</span></li>' : ''}
        <li class="page-item">
          <a class="page-link" href="#" data-page="${last}">${last}</a>
        </li>
      `;
    }
    
    // Botón Siguiente
    paginationHtml += `
      <li class="page-item ${current === last ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${current + 1}" aria-label="Siguiente">
          &raquo;
        </a>
      </li>
    `;
    
    paginationHtml += `
        </ul>
      </nav>
    `;
    
    return paginationHtml;
  }

  // Función para obtener y mostrar bicis paginadas
  async function fetchBicisPorStock(page = 1) {
    const idStock = document.getElementById('stock_buscar').value;
    if (!idStock) return;
    
    const resultadoDiv = document.getElementById('resultadoBusquedaStock');
    const paginationDiv = document.getElementById('paginacionStock');
    
    resultadoDiv.innerHTML = `
      <div class="text-center py-3">
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-2">Buscando bicicletas...</p>
      </div>
    `;
    paginationDiv.innerHTML = '';
    
    try {
      const response = await fetch(
        `{{ route('Busquedas.busStock') }}?stock=${encodeURIComponent(idStock)}&page=${page}`
      );
      const data = await response.json();
      const bicis = data.bicis.data || [];
      currentStockPage = data.bicis.current_page;
      totalStockPages = data.bicis.last_page;
      currentStockId = idStock;
      
      if (bicis.length > 0) {
        let html = `
          <div class="table-container">
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
              <td>${b.num_chasis || 'N/A'}</td>
              <td>${b.num_motor || 'N/A'}</td>
              <td>${b.modelo?.nombre_modelo || 'N/A'}</td>
              <td>${b.color?.nombre_color || 'N/A'}</td>
              <td>${b.voltaje || 'N/A'}</td>
              <td>${b.tipo_stock?.nombre_stock || 'N/A'}</td>
            </tr>
          `;
        });
        
        html += '</tbody></table></div>';
        resultadoDiv.innerHTML = html;
        
        // Generar paginación solo si hay más de una página
        if (totalStockPages > 1) {
          paginationDiv.innerHTML = generatePagination(currentStockPage, totalStockPages);
          
          // Vincular eventos de paginación
          paginationDiv.querySelectorAll('a.page-link[data-page]').forEach(link => {
            link.addEventListener('click', e => {
              e.preventDefault();
              const p = parseInt(e.target.getAttribute('data-page'), 10);
              if (p !== currentStockPage) {
                fetchBicisPorStock(p);
              }
            });
          });
        }
      } else {
        resultadoDiv.innerHTML = `
          <div class="alert alert-warning text-center">
            No se encontraron bicicletas para el stock seleccionado.
          </div>
        `;
      }
    } catch (error) {
      console.error('Error al buscar bicicletas:', error);
      resultadoDiv.innerHTML = `
        <div class="alert alert-danger text-center">
          Error al cargar los resultados. Por favor, intente nuevamente.
        </div>
      `;
    }
  }

  // Manejar envío del formulario
  document.getElementById('formBuscarBiciStock')
    .addEventListener('submit', function (e) {
      e.preventDefault();
      fetchBicisPorStock(1);
    });
</script>