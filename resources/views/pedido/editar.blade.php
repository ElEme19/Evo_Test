@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-2 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-9 col-xl-8">
      <h1 class="h4 fw-bold mb-3">
        <i class="bi bi-pencil-square me-2"></i>Editar Pedido #{{ $pedido->id_pedido }}
      </h1>

      <form method="POST" action="{{ route('pedido.actualizar', $pedido->id_pedido) }}" id="formEditarPedido" class="bg-white p-3 rounded-3 border">
        @csrf
        @method('PUT')

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
          @method('DELETE')
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

<!-- Script Eliminar -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
    const textoChasis = document.getElementById('textoChasis');
    const formEliminar = document.getElementById('formEliminarBici');

    document.querySelectorAll('.btn-eliminar-bici').forEach(btn => {
        btn.addEventListener('click', () => {
            const chasis = btn.dataset.chasis;
            const idPedido = btn.dataset.id;

            textoChasis.textContent = `Serie: ${chasis}`;
            formEliminar.action = `/pedido/${idPedido}/eliminar-bici/${chasis}`;
            modalEliminar.show();
        });
    });

    // Escáner de Bicicleta
    const numChasisInput = document.getElementById('num_chasis');
    numChasisInput.focus();
    numChasisInput.addEventListener('input', async () => {
        const chasis = numChasisInput.value.trim().toUpperCase();
        if (chasis.length === 17) {
            await buscarYAgregar(chasis);
        }
    });

    async function buscarYAgregar(chasis) {
        try {
            const res = await fetch(`/Bicicleta/buscarC?num_chasis=${encodeURIComponent(chasis)}`);
            const data = await res.json();

            if (!data.success || !data.bici) {
                alert('No se encontró la bicicleta.');
                return;
            }

            const bici = data.bici;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('pedido.bici.agregar', $pedido->id_pedido) }}";
            form.innerHTML = `
                @csrf
                <input type="hidden" name="num_chasis" value="${bici.num_chasis}">
            `;
            document.body.appendChild(form);
            form.submit();
        } catch (e) {
            alert('Error al buscar bicicleta: ' + e.message);
        } finally {
            numChasisInput.value = '';
        }
    }
});
</script>
@endsection
