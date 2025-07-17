@extends('layout.app')

@section('conten-wrapper')
<style>
  :root {
    --primary: #16a34a;
    --primary-light: #dcfce7;
    --text: #1f2937;
    --text-light: #6b7280;
    --border: #e5e7eb;
    --success: #22c55e;
    --warning: #eab308;
    --danger: #ef4444;
  }

  body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background-color: #f9fafb;
  }

  .inventory-header {
    background: white;
    border-radius: 12px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }

  .refresh-btn {
    background: white;
    border: 1px solid var(--border);
    color: var(--text-light);
    padding: 0.7rem 1.5rem;
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

  .color-chip {
    background-color: #eafaf1;
    color: var(--primary);
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
  }

  .mini-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.75rem;
    background: white;
    border: 1px solid var(--border);
  }
  .mini-table thead th,
  .mini-table tbody td {
    padding: 0.75rem;
    font-size: 0.9rem;
    border-bottom: 1px solid var(--border);
    text-align: left;
  }

  .volt-no { color: var(--danger); }
  .volt-yes { color: var(--warning); }

  .status-low { color: var(--danger); font-weight: bold; }
  .status-medium { color: var(--warning); font-weight: bold; }
  .status-high { color: var(--success); font-weight: bold; }

  .search-input {
    padding: 0.65rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    margin-bottom: 1rem;
  }

  .group-header:hover {
    background: #f9fafb;
    cursor: pointer;
  }
  .collapse-icon {
    margin-right: 0.5rem;
    transition: transform 0.2s;
  }
  .opened .collapse-icon {
    transform: rotate(180deg);
  }
</style>

<div class="container mt-4 px-3 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <div class="inventory-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="bi bi-box-seam fs-4 me-3"></i>
          <h1 class="h4 fw-bold mb-0 text-gray-800">Gestión de Inventario</h1>
        </div>
        <button onclick="location.reload()" class="refresh-btn">
          <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
        </button>
      </div>

      <input id="searchInput" type="text" class="search-input" placeholder="Buscar modelos, colores...">

      <div class="inventory-card">
        <table class="table" id="inventoryTable">
          <thead>
            <tr>
              <th>Modelo</th>
              <th>Color</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            @php
              $grouped = [];
              foreach ($bicicletas as $item) {
                $key = "{$item->nombre_modelo}|{$item->nombre_color}";
                if (!isset($grouped[$key])) {
                  $grouped[$key] = [
                    'modelo' => $item->nombre_modelo,
                    'color'  => $item->nombre_color,
                    'items'  => [],
                    'total'  => 0
                  ];
                }
                $grouped[$key]['items'][] = $item;
                $grouped[$key]['total'] += 1;
              }
            @endphp

            @forelse($grouped as $idx => $group)
              <tr class="group-header" data-index="{{ $idx }}">
                <td>
                  <i class="collapse-icon bi bi-chevron-down"></i>
                  <span class="model-text">{{ $group['modelo'] }}</span>
                </td>
                <td>
                  <span class="color-chip">{{ $group['color'] }}</span>
                </td>
                <td class="text-end fw-bold">
                  <span class="total-text">{{ $group['total'] }}</span>
                </td>
              </tr>

              <tr class="detail-row" data-index="{{ $idx }}" style="display:none;">
                <td colspan="3">
                  <table class="mini-table">
                    <thead>
                      <tr>
                        <th>Chasis</th>
                        <th>Voltaje</th>
                        <th>Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($group['items'] as $item)
                        <tr>
                          <td class="fw-monospace">{{ $item->num_chasis }}</td>
                          <td>
                            <i class="bi {{ $item->tipo_voltaje === 'Sin voltaje'
                                         ? 'bi-x-circle volt-no'
                                         : 'bi-lightning-fill volt-yes' }}"></i>
                            {{ $item->tipo_voltaje }}
                          </td>
                          <td class="{{ $group['total'] < 30
                                       ? 'status-low'
                                       : ($group['total'] < 50 ? 'status-medium' : 'status-high') }}">
                            <i class="bi {{ $group['total'] < 30
                                         ? 'bi-exclamation-circle'
                                         : ($group['total'] < 50 ? 'bi-exclamation-triangle' : 'bi-check-circle') }}"></i>
                            {{ $group['total'] < 30
                              ? 'Bajo stock'
                              : ($group['total'] < 50 ? 'Stock medio' : 'Disponible') }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-4">
                  No hay bicicletas disponibles.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if(count($bicicletas) > 0)
          <div class="card-footer d-flex justify-content-between align-items-center">
            <small>
              <i class="bi bi-grid-3x3-gap me-1"></i>
              {{ count($grouped) }} grupos / {{ count($bicicletas) }} bicicletas
            </small>
            <small>
              <i class="bi bi-box-seam me-1"></i>
              Total: {{ count($bicicletas) }} unidades
            </small>
          </div>
        @endif

      </div>

    </div>
  </div>
</div>

<script>
(function(){
  const searchInput = document.getElementById('searchInput');
  const rows        = document.querySelectorAll('.group-header');
  const details     = document.querySelectorAll('.detail-row');

  // Toggle detail rows
  rows.forEach((r,i) => {
    r.addEventListener('click', () => {
      const detail = details[i];
      const icon   = r.querySelector('.collapse-icon');
      if (detail.style.display === 'none') {
        detail.style.display = '';
        icon.classList.add('opened');
      } else {
        detail.style.display = 'none';
        icon.classList.remove('opened');
      }
    });
  });

  // Search filter
  searchInput.addEventListener('input', () => {
    const term = searchInput.value.toLowerCase().trim();
    rows.forEach((r,i) => {
      const model = r.querySelector('.model-text').textContent.toLowerCase();
      const color = r.querySelector('.color-chip').textContent.toLowerCase();
      if (model.includes(term) || color.includes(term)) {
        r.style.display = '';
        details[i].style.display = 'none';
      } else {
        r.style.display = 'none';
        details[i].style.display = 'none';
      }
    });
  });
})();
</script>
@endsection
