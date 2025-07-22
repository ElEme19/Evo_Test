@extends('layout.app')

@section('conten-wrapper')
<style>
  :root {
    --primary: #0ea5e9;
    --primary-light: #e0f2fe;
    --text: #1f2937;
    --text-light: #6b7280;
    --border: #e5e7eb;
    --success: #22c55e;
    --warning: #eab308;
    --danger: #ef4444;
  }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    background-color: #f9fafb;
  }

  .inventory-header {
    background: white;
    border-radius: 12px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
    background-color: #EAFAF1;
    color: var(--success);
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
  }

  .status-low {
    color: var(--danger);
    font-weight: bold;
  }

  .status-medium {
    color: var(--warning);
    font-weight: bold;
  }

  .status-high {
    color: var(--success);
    font-weight: bold;
  }

  .search-input {
    padding: 0.65rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    margin-bottom: 1rem;
  }

  .profile-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
  }

  table.table {
    border-collapse: collapse;
    width: 100%;
  }

  table.table td {
    background-color: #fff;
    border: none;
    border-bottom: 1px solid var(--border);
  }

  table.table tr:last-child td {
    border-bottom: none;
  }
</style>



<div class="container mt-4 px-3 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <div class="inventory-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="bi bi-tools fs-4 me-3"></i>
          <h1 class="h4 fw-bold mb-0 text-gray-800">Inventario de Piezas</h1>
        </div>
        <button onclick="location.reload()" class="refresh-btn">
          <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
        </button>
      </div>

      <input id="searchInput" type="text" class="search-input" placeholder="Buscar modelos, colores o piezas...">

      <table class="table">
        <thead>
          <tr>
            <th>Modelo</th>
            <th>Color</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Foto</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($piezas as $pieza)
          <tr>
            <td>{{ $pieza->nombre_modelo }}</td>
            <td><span class="color-chip">{{ $pieza->color ?: 'No aplica' }}</span></td>
            <td>{{ $pieza->nombre_pieza }}</td>
            <td>{{ $pieza->descripcion_general }}</td>
            <td class="
              @if($pieza->cantidad > 100) text-success fw-bold
              @elseif($pieza->cantidad > 20) text-warning fw-bold
              @else text-danger fw-bold
              @endif
            ">
              @if($pieza->cantidad > 100)
                <i class="bi bi-check-circle-fill me-1"></i>
              @elseif($pieza->cantidad > 20)
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
              @else
                <i class="bi bi-x-circle-fill me-1"></i>
              @endif
              {{ $pieza->cantidad }}
            </td>

            <td>{{ $pieza->Unidad }}</td>
            <td class="text-center">
              @if($pieza->foto_pieza)
              <img src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
                   alt="Foto"
                   class="profile-img rounded"
                   style="cursor:pointer"
                   data-bs-toggle="modal"
                   data-bs-target="#modalImagen"
                   data-src="{{ route('pieza.imagen', ['path' => $pieza->foto_pieza]) }}"
                   data-nombre="{{ $pieza->nombre_pieza }}">
              @else
              <div class="profile-img rounded bg-light d-flex align-items-center justify-content-center">
                <i class="bi bi-image text-muted"></i>
              </div>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No hay piezas disponibles.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      @if(count($piezas) > 0)
      <div class="card-footer d-flex justify-content-between align-items-center">
        <small><i class="bi bi-tags-fill me-1"></i> Total modelos: {{ $piezas->pluck('nombre_modelo')->unique()->count() }}</small>
        <small><i class="bi bi-nut-fill me-1"></i> Total piezas visibles: {{ count($piezas) }}</small>
        <small><i class="bi bi-tools me-1"></i> Total: {{ $piezas->sum('cantidad') }} unidades</small>
      </div>
      @endif
    </div>
  </div>
</div>

<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h3 class="modal-title text-center w-100" id="modalImagenLabel"></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagenAmpliada" src="" class="img-fluid rounded shadow" alt="Imagen ampliada">
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('searchInput').addEventListener('input', function() {
    const term = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(term) ? '' : 'none';
    });
  });

  const modalImagen = document.getElementById('modalImagen');
  modalImagen.addEventListener('show.bs.modal', function (event) {
    const img = event.relatedTarget;
    const src = img.getAttribute('data-src');
    const nombre = img.getAttribute('data-nombre');

    const modalImg = modalImagen.querySelector('#imagenAmpliada');
    const modalTitle = modalImagen.querySelector('#modalImagenLabel');

    modalImg.src = src;
    modalTitle.textContent = nombre;
  });
</script>
@endsection
