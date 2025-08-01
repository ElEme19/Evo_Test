@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-2 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-9 col-xl-8">
      <h1 class="h4 fw-bold mb-3">
        <i class="bi bi-pencil-square me-2"></i>Editar Pedido #{{ $pedido->id_pedido }}
      </h1>

     @if (session('success'))
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <!-- ícono SVG -->
            <small class="fw-semibold">{{ session('success') }}</small>
        </div>
    </div>
@endif
@if (session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <small class="fw-semibold">{{ session('error') }}</small>
        </div>
    </div>
@endif




      <form method="POST" action="{{ route('pedido.finalizar', $pedido->id_pedido) }}" id="formEditarPedido" class="bg-white p-3 rounded-3 border">
        @csrf
        @method('POST')

        <!-- Sucursal (solo lectura) -->
        <div class="mb-3">
          <label class="form-label fw-semibold fs-5">
            <i class="bi bi-shop me-1"></i>Sucursal
          </label>
          <input type="text" class="form-control" value="{{ $pedido->sucursal->nombre_sucursal ?? 'N/A' }}" disabled>
        </div>

        <!-- Escáner Bicicleta -->
        <div class="mb-3 p-2 bg-light rounded border">
          <label class="form-label fw-semibold fs-6">
            <i class="bi bi-upc-scan me-1"></i>Escanea Bicicleta
          </label>
          <div class="input-group input-group-sm">
            <input type="text" id="num_chasis" class="form-control" autocomplete="off" placeholder="Escanee el QR">
          </div>
        </div>

        <!-- Tabla Bicicletas -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 fw-semibold mb-0">
              <i class="bi bi-list-check me-2"></i>Bicicletas asignadas
            </h2>
            <span class="badge bg-success text-white small" id="contadorBicis">{{ $pedido->bicicletas->count() }}</span>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle" id="tablaBicicletas">
              <thead class="table-light">
                <tr class="text-center small">
                  <th>#</th>
                  <th>N° Serie</th>
                  <th>Modelo</th>
                  <th>Color</th>
                  <th>Voltaje</th>
                  <th>Stock</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pedido->bicicletas as $i => $bici)
                <tr>
                  <td class="text-center">{{ $i + 1 }}</td>
                  <td class="fw-semibold text-center">{{ $bici->num_chasis }}</td>
                  <td class="text-center">{{ $bici->modelo->nombre_modelo ?? 'N/D' }}</td>
                  <td class="text-center">{{ $bici->color->nombre_color ?? 'N/D' }}</td>
                  <td class="text-center">{{ $bici->voltaje->tipo_voltaje ?? 'Sin Voltaje' }}</td>
                  <td class="text-center">{{ $bici->tipoStock->nombre_stock ?? 'Sin Stock' }}</td>
                  <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger rounded-pill btn-eliminar-bici"
                            data-chasis="{{ $bici->num_chasis }}"
                            data-id="{{ $pedido->id_pedido }}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="text-center mt-3">
          <button type="submit" class="btn btn-success px-4 py-2 small">
            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modales reutilizables -->
@include('pedido.modales-info-error-confirm') 

<!-- Modal Confirmación de Eliminación -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-body text-center">
        <div class="mb-3">
          <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
        </div>
        <h5 class="mb-3">¿Eliminar bicicleta del pedido?</h5>
        <p class="text-muted" id="textoChasis"></p>
        <form id="formEliminarBici" method="POST">
          @csrf
          @method('PUT')
          <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
              Cancelar
            </button>
            <button type="submit" class="btn btn-outline-danger px-3">
              Sí, eliminar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  // Exponer CSRF token para formularios dinámicos
  window.csrfToken = '{{ csrf_token() }}';

  document.addEventListener('DOMContentLoaded', () => {
    // —— 1) Modal de eliminación —————————————————————————————
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
    const formEliminar  = document.getElementById('formEliminarBici');
    const textoChasis   = document.getElementById('textoChasis');

    document.querySelectorAll('.btn-eliminar-bici').forEach(btn => {
      btn.addEventListener('click', () => {
        const chasis   = btn.dataset.chasis;
        const idPedido = btn.dataset.id;

        textoChasis.textContent = `Serie: ${chasis}`;
        formEliminar.action     = `{{ url('/pedido') }}/${idPedido}/eliminar-bici/${encodeURIComponent(chasis)}`;
        modalEliminar.show();
      });
    });

    // —— 2) Escaneo y agregado automático ————————————————————————
    const numChasisInput = document.getElementById('num_chasis');
    numChasisInput.focus();

    numChasisInput.addEventListener('input', async () => {
      const chasis = numChasisInput.value.trim().toUpperCase();
      if (chasis.length !== 17) return;
      await buscarYAgregar(chasis);
    });

    async function buscarYAgregar(chasis) {
      try {
        const res  = await fetch(`/Bicicleta/buscarC?num_chasis=${encodeURIComponent(chasis)}`);
        const data = await res.json();

        if (!data.success || !data.bici) {
          document.getElementById('errorModalBody').textContent = 'No se encontró la bicicleta.';
          new bootstrap.Modal(document.getElementById('errorModal')).show();
          return;
        }

        const bici = data.bici;

        // 2.1) Pertenece a otro pedido?
        if (bici.id_pedido && bici.id_pedido !== "{{ $pedido->id_pedido }}") {
          document.getElementById('errorModalBody').innerHTML =
            `La bicicleta ya pertenece al pedido <strong>#${bici.id_pedido}</strong>`;
          new bootstrap.Modal(document.getElementById('errorModal')).show();
          return;
        }

        // 2.2) Ya agregada en la tabla?
        const yaAgregada = Array.from(
          document.querySelectorAll('#tablaBicicletas tbody td:nth-child(2)')
        ).some(td => td.textContent.trim() === bici.num_chasis);

        if (yaAgregada) {
          document.getElementById('infoModalBody').textContent =
            'La bicicleta ya ha sido agregada a este pedido.';
          new bootstrap.Modal(document.getElementById('infoModal')).show();
          return;
        }

        // 2.3) Si todo OK, enviar POST para agregar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('pedido.bici.agregar', $pedido->id_pedido) }}`;
        form.innerHTML = `
          <input type="hidden" name="_token" value="${window.csrfToken}">
          <input type="hidden" name="num_chasis" value="${bici.num_chasis}">
        `;
        document.body.appendChild(form);
        form.submit();

      } catch (e) {
        document.getElementById('errorModalBody').textContent =
          'Error al buscar bicicleta: ' + e.message;
        new bootstrap.Modal(document.getElementById('errorModal')).show();
      } finally {
        numChasisInput.value = '';
      }
    }
  });
</script>


@endsection
