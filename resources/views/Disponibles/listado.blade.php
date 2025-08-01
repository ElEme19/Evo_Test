@extends('layout.app')

@section('conten-wrapper')
<style>
  :root {
    --primary: #16a34a;
    --primary-light: #ecfdf5;
    --text: #1f2937;
    --text-light: #6b7280;
    --border: #e5e7eb;
    --table-hover: #f3f4f6;
  }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    -webkit-font-smoothing: antialiased;
    background-color: #f9fafb;
  }

  .inventory-header {
    background: white;
    border-radius: 12px;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }

  .refresh-btn {
    background: white;
    border: 1px solid var(--border);
    color: var(--text-light);
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
  }

  .refresh-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background-color: var(--primary-light);
  }

  .search-input {
    padding: 0.6rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    margin-bottom: 1rem;
    font-size: 0.95rem;
  }

  .inventory-card table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border);
    width: 100%;
  }

  .inventory-card th {
  background: var(--primary);
  color: white;
  font-weight: 600;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #0f511dff; /* borde más oscuro opcional */
}


  .inventory-card td {
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: var(--text);
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
  }

  .inventory-card tbody tr:hover {
    background-color: var(--table-hover);
  }

  .card-footer {
    background: #fff;
    border-top: 1px solid var(--border);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    color: var(--text-light);
  }

  .fw-bold {
    font-weight: 600 !important;
  }

  .text-end {
    text-align: right;
  }
  .table thead tr {
  background-color: var(--success);
  color: white;
}

</style>

<div class="container mt-4 px-3 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <div class="inventory-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="bi bi-box-seam fs-4 me-3 text-success"></i>
          <h1 class="h5 fw-bold mb-0 text-gray-800">Inventario de Bicicletas</h1>
        </div>
        <button onclick="location.reload()" class="refresh-btn">
          <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
        </button>
      </div>

      <input id="searchInput" type="text" class="search-input" placeholder="Buscar modelo o color...">

      <div class="inventory-card">
        <table class="table" id="inventoryTable">
          <thead>
            <tr>
              <th>Modelo</th>
              <th>Color</th>
              <th>Voltaje</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            @php
              $grouped = [];
              foreach ($bicicletas as $item) {
                $key = "{$item->nombre_modelo}|{$item->nombre_color}|{$item->tipo_voltaje}";
                if (!isset($grouped[$key])) {
                  $grouped[$key] = [
                    'modelo' => $item->nombre_modelo,
                    'color' => $item->nombre_color,
                    'voltaje' => $item->tipo_voltaje,
                    'total' => 0
                  ];
                }
                $grouped[$key]['total'] += 1;
              }
            @endphp

            @forelse($grouped as $group)
              <tr>
                <td>{{ $group['modelo'] }}</td>
                <td>{{ $group['color'] }}</td>
                <td>{{ $group['voltaje'] }}</td>
                <td class="text-end fw-bold">{{ $group['total'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">No hay bicicletas disponibles.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if(count($bicicletas) > 0)
        <div class="card-footer d-flex justify-content-between align-items-center">
          <small><i class="bi bi-grid-3x3-gap me-1"></i>{{ count($grouped) }} grupos / {{ count($bicicletas) }} bicicletas</small>
          <small><i class="bi bi-box-seam me-1"></i>Total: {{ count($bicicletas) }} unidades</small>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const searchInput = document.getElementById('searchInput');
  const rows = document.querySelectorAll('#inventoryTable tbody tr');

  searchInput.addEventListener('input', () => {
    const term = searchInput.value.toLowerCase().trim();
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(term) ? '' : 'none';
    });
  });
})();
</script>
@endsection
