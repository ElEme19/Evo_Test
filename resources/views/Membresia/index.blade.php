@extends('layout.app')

@section('conten-wrapper')
<style>
  /* Estilos consistentes */
  .table td, .table th {
    padding: .5rem .75rem !important;
  }
  
  .card-header {
    padding: .75rem 1.25rem !important;
  }
  
  .table-hover tbody tr {
    line-height: 1.3 !important;
  }
  
  .btn-outline-success:hover {
    background-color: #198754;
    color: white !important;
  }
  
  .badge-title {
    font-size: 1rem;
    padding: 0.5em 1em;
  }
  
  /* Contenedor de búsqueda mejorado */
  .search-container {
    max-width: 500px;
    margin: 0 auto 1.5rem;
  }
  
  /* Grupo de acciones superiores */
  .action-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
  }
</style>

<div class="container mt-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

     
        <!-- HEADER PRINCIPAL SIMPLIFICADO -->
        <header class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 bg-white rounded shadow-sm">
        <!-- TÍTULO E ICONO (Izquierda) -->
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <div class="me-3 p-2bg-success bg-opacity-10" aria-hidden="true">
            <i class="bi bi-credit-card text-success fs-3" role="img" aria-label="Icono de membresías"></i>
            </div>
            <div>
            <h1 class="h4 fw-bold mb-0">Membresías</h1>
            <p class="text-muted small mb-0">Administración de tipos de membresía</p>
            </div>
        </div>
        
        <!-- BOTÓN NUEVA MEMBRESÍA (Derecha) -->
        @if (auth()->user()->rol == 0)
            <div class="ms-md-auto">
            <a href="{{ route('Membresia.create') }}" 
                class="btn btn-success d-flex align-items-center justify-content-center shadow-sm py-2 px-3 rounded"
                aria-label="Crear nueva membresía">
                <i class="bi bi-plus-circle-fill me-2" aria-hidden="true"></i>
                <span>Nueva Membresía</span>
            </a>
            </div>
        @endif
        </header>

     <!-- ALERTAS CENTRADAS -->
        @if (session('success'))
        <div class="alert alert-success d-flex align-items-center py-2 px-3 rounded-3 shadow-sm mb-3 mx-auto" role="alert" style="max-width: fit-content;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <small class="fw-semibold">{{ session('success') }}</small>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center py-2 px-3 rounded-3 shadow-sm mb-3 mx-auto" role="alert" style="max-width: fit-content;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <small class="fw-semibold">{{ session('error') }}</small>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

      

      <!-- TABLA DE MEMBRESÍAS -->
      <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
          <h2 class="h6 mb-0 text-secondary">
            <i class="bi bi-list-check me-2"></i>Membresías Registradas
          </h2>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaMembresias">
              <thead class="bg-light">
                <tr>
                  <th class="fw-semibold text-center">ID Membresía</th>
                  <th class="fw-semibold text-center">Descripción General</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($membresias as $m)
                  <tr>
                    <td class="text-center fw-semibold">{{ $m->id_membresia }}</td>
                    <td class="text-center">{{ $m->descripcion_general }}</td>
                    <td class="text-center">
                      <div class="action-buttons">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizar{{ $m->id_membresia }}">
                          <i class="bi bi-pencil-square me-1"></i>Editar
                        </button>
                        @include('Membresia.actualizar', ['m' => $m])
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-5">
                      <div class="d-flex flex-column align-items-center text-muted">
                        <i class="bi bi-people display-5 mb-3"></i>
                        <span>No hay membresías registradas</span>
                        @if (auth()->user()->rol == 0)
                          <a href="{{ route('Membresia.create') }}" class="btn btn-link mt-2">
                            Crear primera membresía
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputBuscar');
    const tabla = document.getElementById('tablaMembresias').getElementsByTagName('tbody')[0];

    const normalizarTexto = (texto) => {
      return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, " ")
        .trim();
    };

    input.addEventListener('input', () => {
      const filtro = normalizarTexto(input.value);
      const filas = tabla.querySelectorAll('tr');

      filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        let coincide = false;

        celdas.forEach(celda => {
          const textoCelda = normalizarTexto(celda.textContent);
          if (textoCelda.includes(filtro)) {
            coincide = true;
          }
        });

        fila.style.display = coincide ? '' : 'none';
      });
    });
  });
</script>

@endsection