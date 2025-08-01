@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-2 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-9 col-xl-8">

      <style>
        .modal-content { border-radius: 0.8rem; overflow: hidden; }
        .modal-header { border-bottom: none; padding: 1.25rem 1.5rem; }
        .modal-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
        .modal-body { padding: 1.5rem; font-size: 1.05rem; }
        .modal-footer { border-top: none; padding: 1rem 1.5rem; }
        .btn-rounded { border-radius: 50px; padding: 0.5rem 1.25rem; }
        .modal-title { font-weight: 600; }
      </style>

      <!-- Encabezado -->
      <header class="text-center mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">
          <i class="bi bi-box-seam me-2"></i>Nuevo Pedido de Piezas
        </h1>
      </header>

      <!-- Alertas -->
      <div class="alert-container mb-4">
        @if (session('success'))
          <div class="text-center">
            <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
              <i class="bi bi-check-circle me-2"></i>
              <small class="fw-semibold">{{ session('success') }}</small>
            </div>
          </div>
        @endif

        @if (session('error'))
          <div class="text-center">
            <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
              <i class="bi bi-exclamation-triangle me-2"></i>
              <small class="fw-semibold">{{ session('error') }}</small>
            </div>
          </div>
        @endif
      </div>

      <!-- Formulario -->
      <form method="POST" action="{{ route('pedidos_piezas.store') }}" id="formPedidoPiezas" class="bg-white p-3 rounded-3 border">
        @csrf

        <!-- Selección de Pedido -->
        <div class="mb-3">
          <label for="id_pedido" class="form-label fw-semibold fs-5">
            <i class="bi bi-card-checklist me-1"></i>Pedido
          </label>
          <select name="id_pedido" id="id_pedido" class="form-select form-select-sm" required>
            <option value="" selected disabled>Seleccione un pedido</option>
            @foreach($pedidos as $pedido)
              <option value="{{ $pedido->id_pedido }}">{{ $pedido->id_pedido }} - {{ $pedido->sucursal->nombre_sucursal ?? 'N/D' }}</option>
            @endforeach
          </select>
        </div>

        <!-- Código o nombre de la pieza -->
        <div class="mb-3 p-2 bg-light rounded border">
          <label for="pieza_code" class="form-label fw-semibold fs-6">
            <i class="bi bi-search me-1"></i>Buscar Pieza (ID o Nombre)
          </label>
          <div class="input-group input-group-sm">
            <input type="text" id="pieza_code" class="form-control" autocomplete="off" placeholder="Ingrese código o nombre de pieza">
           
          </div>
        </div>

        <!-- Tabla Piezas -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 fw-semibold mb-0">
              <i class="bi bi-list-check me-2"></i>Piezas Seleccionadas
            </h2>
            <span class="badge bg-success text-white small" id="contadorPiezas">0</span>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle" id="tablaPiezas">
              <thead class="table-light text-center small">
                <tr>
                  <th>#</th>
                  <th>ID Pieza</th>
                  <th>Modelo</th>
                  <th>Nombre</th>
                  <th>Color</th>
                  <th>Cantidad</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- Botón Finalizar -->
        <div class="text-center mt-3">
          <button type="submit" class="btn btn-success px-4 py-2 small" id="btnFinalizarPiezas" disabled>
            <i class="bi bi-check-circle me-1"></i>Finalizar Pedido de Piezas
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Confirmación -->
<div class="modal fade" id="confirmModalPieza" tabindex="-1" aria-labelledby="confirmModalLabelPieza" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light">
        <div class="modal-icon bg-success text-white rounded-circle me-3">
          <i class="fas fa-question"></i>
        </div>
        <h5 class="modal-title text-dark" id="confirmModalLabelPieza">Confirmar acción</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body py-4" id="confirmModalBodyPieza">
        <div class="d-flex align-items-center">
          <i class="fas fa-info-circle text-primary me-3 fs-4"></i>
          <p class="mb-0">¿Desea agregar esta pieza al pedido?</p>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cancelar
        </button>
        <button type="button" class="btn btn-outline-success rounded-pill px-4" id="confirmAddBtnPieza">
          <i class="fas fa-check me-2"></i>Confirmar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Info -->
<div class="modal fade" id="infoModalPieza" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-info-circle fs-2 text-primary mb-3"></i>
        <p id="infoModalBodyPieza" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-primary mt-3" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="errorModalPieza" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-x-circle fs-2 text-danger mb-3"></i>
        <p id="errorModalBodyPieza" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-danger mt-3" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const piezaInput       = document.getElementById('pieza_code');
  const pedidoSelect     = document.getElementById('id_pedido');
  const tabla            = document.querySelector('#tablaPiezas tbody');
  const btnFinalizar     = document.getElementById('btnFinalizarPiezas');
  const formPedidoPiezas = document.getElementById('formPedidoPiezas');
  const contadorPiezas   = document.getElementById('contadorPiezas');

  const infoModal      = new bootstrap.Modal(document.getElementById('infoModalPieza'));
  const errorModal     = new bootstrap.Modal(document.getElementById('errorModalPieza'));

  let listaPiezas = [];
  let isBuscando  = false;

  // Habilita el input cuando se selecciona un pedido
  pedidoSelect.addEventListener('change', () => {
    piezaInput.disabled    = !pedidoSelect.value;
    piezaInput.value       = '';
    listaPiezas            = [];
    renderizarTabla();
    btnFinalizar.disabled  = true;
    if (pedidoSelect.value) piezaInput.focus();
  });

  // Al escribir, buscar pieza
  piezaInput.addEventListener('keyup', (e) => {
    const termino = piezaInput.value.trim();
    if (termino && !isBuscando) {
      buscarPieza(termino);
    }
  });

  async function buscarPieza(termino) {
    isBuscando = true;
    try {
      const url = `/pieza/buscar?term=${encodeURIComponent(termino)}`;
      const res = await fetch(url);
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        throw new Error('Respuesta no es JSON válida');
      }
      const data = await res.json();

      if (!res.ok || !data.success || !data.pieza) {
        mostrarErrorModal(data.message || 'No se encontró ninguna pieza con ese código o nombre.');
        return;
      }

      const nueva = {
        id_pieza:     data.pieza.id_pieza,
        nombre_pieza: data.pieza.nombre_pieza,
        modelo:       data.pieza.modelo       ?? 'N/D',
        color:        data.pieza.color        ?? 'N/D',
        cantidad:     1
      };

      // Si ya existe, sólo incrementar cantidad
      const existe = listaPiezas.find(p => p.id_pieza === nueva.id_pieza);
      if (existe) {
        existe.cantidad++;
      } else {
        // Agregar nueva pieza directamente
        listaPiezas.push(nueva);
      }

      renderizarTabla();
    } catch (error) {
      mostrarErrorModal('Error al buscar la pieza: ' + error.message);
    } finally {
      isBuscando       = false;
      piezaInput.value = '';
      piezaInput.focus();
    }
  }

  // Renderiza la tabla según listaPiezas
  function renderizarTabla() {
    tabla.innerHTML = '';
    listaPiezas.forEach((pieza, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-center">${i + 1}</td>
        <td class="text-center fw-semibold">${pieza.id_pieza}</td>
        <td class="text-center">${pieza.modelo}</td>
        <td class="text-center">${pieza.nombre_pieza}</td>
        <td class="text-center">${pieza.color}</td>
        <td class="text-center">
          <input type="number" min="1" value="${pieza.cantidad}" data-index="${i}"
                 class="form-control form-control-sm cantidad-input"
                 style="width: 70px; margin: auto;">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                  onclick="quitarPieza('${pieza.id_pieza}')">
            <i class="bi bi-trash"></i>
          </button>
        </td>`;
      tabla.appendChild(tr);
    });

    contadorPiezas.textContent = listaPiezas.length;
    btnFinalizar.disabled      = listaPiezas.length === 0;

    document.querySelectorAll('.cantidad-input').forEach(input => {
      input.addEventListener('change', (e) => {
        const index = e.target.dataset.index;
        const val   = parseInt(e.target.value);
        if (val >= 1) {
          listaPiezas[index].cantidad = val;
        } else {
          e.target.value = listaPiezas[index].cantidad;
        }
      });
    });
  }

  // Quitar pieza completamente
  window.quitarPieza = function(id_pieza) {
    listaPiezas = listaPiezas.filter(p => p.id_pieza !== id_pieza);
    renderizarTabla();
  };

  function mostrarInfoModal(msg) {
    document.getElementById('infoModalBodyPieza').textContent = msg;
    infoModal.show();
  }

  function mostrarErrorModal(msg) {
    document.getElementById('errorModalBodyPieza').textContent = msg;
    errorModal.show();
  }

  // Enviar formulario con JSON de piezas
  formPedidoPiezas.addEventListener('submit', (e) => {
    e.preventDefault();

    if (!pedidoSelect.value) {
      mostrarErrorModal('Debe seleccionar un pedido.');
      return;
    }
    if (listaPiezas.length === 0) {
      mostrarErrorModal('Debe agregar al menos una pieza al pedido.');
      return;
    }

    const inputHidden = document.createElement('input');
    inputHidden.type  = 'hidden';
    inputHidden.name  = 'piezas_json';
    inputHidden.value = JSON.stringify(listaPiezas);
    formPedidoPiezas.appendChild(inputHidden);

    formPedidoPiezas.submit();
  });
});
</script>


@endsection
