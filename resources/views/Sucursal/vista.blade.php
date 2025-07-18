@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Ajustes de tabla */
  .table td, .table th { padding: .3rem .5rem !important; }
  .card-header, .card-footer { padding: .75rem 1rem !important; }
  .table-hover tbody tr { line-height: 1.2 !important; }
  /* Imágenes más pequeñas para sucursales */
  .fachada-img {
    width: 40px !important;
    height: 40px !important;
    object-fit: cover !important;
  }
</style>

<div class="container-fluid px-2 px-md-4 py-3">
  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
      <div class="me-3 p-2 bg-success bg-opacity-10">
        <i class="bi bi-building text-success fs-3"></i>
      </div>
      <div>
        <h1 class="h4 fw-bold mb-0">Sucursales</h1>
        <p class="text-muted small mb-0">Gestión de sucursales</p>
      </div>
    </div>
    @if(auth()->user()->rol == 0)
      <a href="{{ route('Sucursal.crear') }}"
         class="btn btn-success btn-sm d-flex align-items-center shadow-sm py-2 px-3 rounded">
        <i class="bi bi-plus-circle-fill me-2"></i>
        <span>Nueva Sucursal</span>
      </a>
    @endif
  </div>

  <!-- ALERTAS -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- TABLA -->
  <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom">
      <h2 class="h6 mb-0 text-secondary">
        <i class="bi bi-list-check me-2"></i>Listado de Sucursales
      </h2>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaSucursales">
          <thead class="bg-light">
            <tr>
              <th class="fw-semibold text-center">ID</th>
              <th class="fw-semibold text-center">Cliente</th>
              <th class="fw-semibold">Nombre</th>
              <th class="fw-semibold">Localización</th>
              <th class="fw-semibold text-center">Fachada</th>
              <th class="fw-semibold text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sucursales as $sucursal)
              <tr>
                <td class="text-center">{{ $sucursal->id_sucursal }}</td>
                <td class="text-center">{{ $sucursal->cliente->nombre ?? 'Sin cliente' }}</td>
                <td>{{ $sucursal->nombre_sucursal }}</td>
                <td>{{ $sucursal->localizacion }}</td>
                <td class="text-center">
                  @if($sucursal->foto_fachada)
                    <img src="{{ route('sucursal.imagen', ['path' => $sucursal->foto_fachada]) }}"
                         alt="Fachada" class="fachada-img rounded shadow-sm">
                  @else
                    <span class="text-muted">Sin foto</span>
                  @endif
                </td>
                
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-building display-4 mb-3"></i><br>
                  No hay sucursales registradas.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- FILTRADO DINÁMICO -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputBuscar');
    const tabla = document.getElementById('tablaSucursales').getElementsByTagName('tbody')[0];
    const normalizarTexto = texto => texto
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/\s+/g, ' ')
      .trim();

    if (input) {
      input.addEventListener('input', () => {
        const filtro = normalizarTexto(input.value);
        Array.from(tabla.rows).forEach(fila => {
          const textoFila = Array.from(fila.cells)
            .map(td => normalizarTexto(td.textContent))
            .join(' ');
          fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
      });
    }
  });
</script>
@endsection
