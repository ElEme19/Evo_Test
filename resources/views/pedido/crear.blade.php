@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-2 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-9 col-xl-8">
                <style>
                    .modal-content {
                        border-radius: 0.8rem;
                        overflow: hidden;
                    }
                    
                    .modal-header {
                        border-bottom: none;
                        padding: 1.25rem 1.5rem;
                    }
                    
                    .modal-icon {
                        width: 32px;
                        height: 32px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    
                    .modal-body {
                        padding: 1.5rem;
                        font-size: 1.05rem;
                    }
                    
                    .modal-footer {
                        border-top: none;
                        padding: 1rem 1.5rem;
                    }
                    
                    .btn-rounded {
                        border-radius: 50px;
                        padding: 0.5rem 1.25rem;
                    }
                    
                    .modal-title {
                        font-weight: 600;
                    }
                </style>
            <!-- Encabezado -->
            <header class="text-center mb-4">
                <h1 class="h3 mb-0 text-success fw-bold">
                    <i class="bi bi-truck me-2"></i>Nuevo Pedido
                </h1>
    
                <!-- <div class="badge bg-light text-dark fs-6 px-2 py-1 border border-secondary">
                    <i class="bi bi-info-circle me-1"></i>Escanea las bicicletas
                </div> -->
            </header>

            <!-- Alertas -->
            <div class="alert-container mb-4">
                @if (session('success'))
                    <div class="text-center">
                        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            <small class="fw-semibold">{{ session('success') }}</small>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="text-center">
                        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <small class="fw-semibold">{{ session('error') }}</small>
                        </div>
                    </div>
                @endif
            </div>


 
            <!-- Formulario -->
            <form method="POST" action="{{ route('pedido.store') }}" id="formPedido" class="bg-white p-3 rounded-3 border">
                @csrf

                <!-- Sucursal -->
                <div class="mb-3">
                    <label for="id_sucursal" class="form-label fw-semibold fs-5">
                        <i class="bi bi-shop me-1"></i>Sucursal Destino
                    </label>
                    <select name="id_sucursal" id="id_sucursal" class="form-select form-select-sm" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Escáner Bicicleta -->
                <div class="mb-3 p-2 bg-light rounded border">
                    <label for="num_chasis" class="form-label fw-semibold fs-6">
                        <i class="bi bi-upc-scan me-1"></i>Escanea Bicicleta
                    </label>
                    <div class="input-group input-group-sm">
                       
                        <input type="text" id="num_chasis" class="form-control" 
                               autocomplete="off" placeholder="Escanee el QR" disabled>
                    </div>
                    
                </div>

                <!-- Tabla Bicicletas -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h6 fw-semibold mb-0">
                            <i class="bi bi-list-check me-2"></i>Bicicletas
                        </h2>
                        <span class="badge bg-success text-white small" id="contadorBicis">0</span>
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón Finalizar -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2 small" id="btnFinalizar" disabled>
                        <i class="bi bi-check-circle me-1"></i>Finalizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <div class="modal-icon bg-success text-white rounded-circle me-3">
                    <i class="fas fa-question"></i>
                </div>
                <h5 class="modal-title text-dark" id="confirmModalLabel">Confirmar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4" id="confirmModalBody">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-3 fs-4"></i>
                    <p class="mb-0">¿Desea agregar esta bicicleta al pedido?</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-outline-success rounded-pill px-4" id="confirmAddBtn">
                    <i class="fas fa-check me-2"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Información -->
<div class="modal fade" id="infoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-info-circle fs-2 text-primary mb-3"></i>
        <p id="infoModalBody" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-primary mt-3" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Error -->
<div class="modal fade" id="errorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-x-circle fs-2 text-danger mb-3"></i>
        <p id="errorModalBody" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-danger mt-3" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const numChasisInput = document.getElementById('num_chasis');
    const sucursalSelect = document.getElementById('id_sucursal');
    const tabla = document.querySelector('#tablaBicicletas tbody');
    const btnFinalizar = document.getElementById('btnFinalizar');
    const formPedido = document.getElementById('formPedido');
    const contadorBicis = document.getElementById('contadorBicis');

    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const confirmAddBtn = document.getElementById('confirmAddBtn');

    let listaBicis = [];
    let currentBici = null;
    let isBuscando = false;

    sucursalSelect.addEventListener('change', () => {
        numChasisInput.disabled = !sucursalSelect.value;
        numChasisInput.value = '';
        listaBicis = [];
        renderizarTabla();
        btnFinalizar.disabled = true;

        if (sucursalSelect.value) numChasisInput.focus();
    });

    numChasisInput.addEventListener('input', async () => {
        const valor = numChasisInput.value.trim().toUpperCase();
        if (valor.length === 17) await procesarBusqueda(valor);
    });

    confirmAddBtn.addEventListener('click', () => {
        if (currentBici) {
            agregarBicicleta(currentBici);
            confirmModal.hide();
            currentBici = null;
        }
    });

    async function procesarBusqueda(valor) {
        if (!valor || isBuscando) return;
        if (listaBicis.some(b => b.num_chasis.toUpperCase() === valor)) {
            mostrarInfoModal('Esta bicicleta ya fue agregada al pedido.');
            numChasisInput.value = '';
            return;
        }

        await buscarBicicleta(valor);
    }

    async function buscarBicicleta(numSerie) {
        isBuscando = true;

        try {
            const url = `/Bicicleta/buscarC?num_chasis=${encodeURIComponent(numSerie)}`;
            const res = await fetch(url);
            const data = await res.json();
            const biciData = data.bici;

            if (!res.ok || !data.success || !biciData || !biciData.num_chasis) {
                mostrarErrorModal('No se encontró ninguna bicicleta con ese número');
                return;
            }

            if (biciData.pedido_asociado) {
                mostrarErrorModal('Esta bicicleta ya tiene un pedido registrado.');
                return;
            }

            const nuevaBici = {
                num_chasis: biciData.num_chasis,
                modelo: biciData.modelo?.nombre_modelo ?? 'N/D',
                color: biciData.color?.nombre_color ?? 'N/D',
                voltaje: biciData.voltaje?.tipo_voltaje ?? biciData.Voltaje?.tipo_voltaje ?? 'Sin Voltaje',
                stock: biciData.tipoStock?.nombre_stock ?? biciData.tipo_stock?.nombre_stock ?? 'Sin Stock'
            };

            agregarBicicleta(nuevaBici);

        } catch (error) {
            console.error('Error:', error);
            mostrarErrorModal('Error al buscar la bicicleta: ' + error.message);
        } finally {
            isBuscando = false;
            numChasisInput.value = '';
            numChasisInput.focus();
        }
    }

    function mostrarModalConfirmacion(bici) {
        document.getElementById('confirmModalBody').innerHTML = `
            <div class="mb-3">
                <p>¿Agregar esta bicicleta al pedido?</p>
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><strong>No. Serie:</strong> ${bici.num_chasis}</li>
                            <li><strong>Modelo:</strong> ${bici.modelo}</li>
                            <li><strong>Color:</strong> ${bici.color}</li>
                            <li><strong>Voltaje:</strong> ${bici.voltaje}</li>
                            <li><strong>Stock:</strong> ${bici.stock}</li>
                        </ul>
                    </div>
                </div>
            </div>`;
        confirmModal.show();
    }

    function agregarBicicleta(bici) {
        listaBicis.push(bici);
        renderizarTabla();
    }

    function renderizarTabla() {
        tabla.innerHTML = '';
        const repeticiones = {};

        listaBicis.forEach(b => {
            const key = b.num_chasis.toUpperCase();
            repeticiones[key] = (repeticiones[key] || 0) + 1;
        });

        listaBicis.forEach((bici, i) => {
            const key = bici.num_chasis.toUpperCase();
            const claseRoja = repeticiones[key] > 1 ? 'table-danger' : '';

            const tr = document.createElement('tr');
            tr.className = claseRoja;
            tr.innerHTML = `
                <td class="text-center">${i + 1}</td>
                <td class="fw-semibold text-center">${bici.num_chasis}</td>
                <td class="text-center">${bici.modelo}</td>
                <td class="text-center">${bici.color}</td>
                <td class="text-center">${bici.voltaje}</td>
                <td class="text-center">${bici.stock}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                            onclick="quitarBici('${bici.num_chasis}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>`;
            tabla.appendChild(tr);
        });

        btnFinalizar.disabled = listaBicis.length === 0;
        contadorBicis.textContent = listaBicis.length;
    }

    window.quitarBici = function (num_chasis) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== num_chasis);
        renderizarTabla();
    };

    function mostrarInfoModal(msg) {
        document.getElementById('infoModalBody').textContent = msg;
        infoModal.show();
    }

    function mostrarErrorModal(msg) {
        document.getElementById('errorModalBody').textContent = msg;
        errorModal.show();
    }

    formPedido.addEventListener('submit', (e) => {
        e.preventDefault();
        if (listaBicis.length === 0) {
            mostrarErrorModal('Debe agregar al menos una bicicleta al pedido');
            return;
        }

        const inputHidden = document.createElement('input');
        inputHidden.type = 'hidden';
        inputHidden.name = 'bicis_json';
        inputHidden.value = JSON.stringify(listaBicis);
        formPedido.appendChild(inputHidden);

        formPedido.submit();
    });
});
</script>

</script>
@endsection