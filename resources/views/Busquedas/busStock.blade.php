<style>
  .pagination .page-link {
    color: #198754; /* Verde Bootstrap */
    background-color: #f8f9fa; /* Fondo claro */
    border-color: #dee2e6;
    transition: all 0.2s ease-in-out;
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
       <nav id="paginacionStock" class="d-flex justify-content-center mt-3" aria-label="Paginación de Bicicletas">

      </div>
    </div>
  </div>
</div>

<script>
  // Cargar tipos de stock cuando se abra el modal
  async function fetchStocks() {
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
  }

  document.getElementById('modalBuscarBiciStock')
    .addEventListener('show.bs.modal', fetchStocks);

  // Función para obtener y mostrar bicis paginadas
  async function fetchBicisPorStock(page = 1) {
    const idStock = document.getElementById('stock_buscar').value;
    const resultadoDiv = document.getElementById('resultadoBusquedaStock');
    const nav = document.getElementById('paginacionStock');
    resultadoDiv.innerHTML = '<div class="text-center py-3"><em>Buscando…</em></div>';
    nav.innerHTML = '';

    const response = await fetch(
      `{{ route('Busquedas.busStock') }}?stock=${encodeURIComponent(idStock)}&page=${page}`
    );
    const data = await response.json();
    const bicis = data.bicis.data || [];

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

      // Generar paginación
      const { current_page, last_page } = data.bicis;
      let pagHtml = '<ul class="pagination justify-content-center">';
      for (let p = 1; p <= last_page; p++) {
        pagHtml += `
          <li class="page-item ${p === current_page ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${p}">${p}</a>
          </li>
        `;
      }
      pagHtml += '</ul>';
      nav.innerHTML = pagHtml;

      // Vincular clicks de paginación
      nav.querySelectorAll('a.page-link').forEach(link => {
        link.addEventListener('click', e => {
          e.preventDefault();
          const p = parseInt(e.target.dataset.page, 10);
          if (p !== current_page) fetchBicisPorStock(p);
        });
      });

    } else {
      resultadoDiv.innerHTML = `<p class="text-danger">No se encontraron bicicletas para el stock seleccionado.</p>`;
    }
  }

  // Manejar envío del formulario
  document.getElementById('formBuscarBiciStock')
    .addEventListener('submit', function (e) {
      e.preventDefault();
      fetchBicisPorStock(1);
    });
</script>
