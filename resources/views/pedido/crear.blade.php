@extends('layout.app')

@section('conten')
<div class="container px-2 px-md-3 py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9 col-xl-8">
                
            <!-- Encabezado -->
            <header class="text-center mb-4">
                <h1 class="h5 fw-bold text-dark">
                    <i class="bi bi-truck me-2"></i>Nuevo Pedido
                </h1>
                <div class="badge bg-light text-dark fs-6 px-2 py-1 border border-secondary">
                    <i class="bi bi-info-circle me-1"></i>Escanea las bicicletas
                </div>
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
                    <label for="id_sucursal" class="form-label fw-semibold small">
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
                    <label for="num_chasis" class="form-label small">
                        <i class="bi bi-upc-scan me-1"></i>Escanea Bicicleta
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-upc-scan"></i>
                        </span>
                        <input type="text" id="num_chasis" class="form-control" 
                               autocomplete="off" placeholder="Serie completa o últimos 4" disabled>
                    </div>
                    <small class="text-muted"><i class="bi bi-keyboard"></i> Presiona Enter</small>
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
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                <i class="fas fa-question-circle text-muted fs-1"></i>
                </div>
                <h5 class="mb-3">Confirmar acción</h5>
                <p class="text-muted mb-4">¿Desea agregar esta bicicleta al pedido?</p>
                <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-sm btn-success px-4" id="confirmAddBtn">
                    Agregar
                </button>
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal de Información -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                <i class="fas fa-info-circle text-primary fs-1"></i>
                </div>
                <h5 class="mb-3">Información</h5>
                <p class="text-muted mb-4" id="infoModalBody">Mensaje de información</p>
                <button type="button" class="btn btn-sm btn-primary px-4" data-bs-dismiss="modal">
                Aceptar
                </button>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal de Error -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                <i class="fas fa-exclamation-circle text-danger fs-1"></i>
                </div>
                <h5 class="mb-3">Error</h5>
                <p class="text-muted mb-4" id="errorModalBody">Mensaje de error</p>
                <button type="button" class="btn btn-sm btn-outline-danger px-4" data-bs-dismiss="modal">
                Cerrar
                </button>
            </div>
            </div>
        </div>
        </div>

        <style>
        .modal-content {
            border-radius: 8px;
        }
        .modal-body {
            padding: 2rem;
        }
        .btn {
            border-radius: 4px;
            min-width: 80px;
        }
        </style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Elementos del DOM
    const numChasisInput = document.getElementById('num_chasis');
    const sucursalSelect = document.getElementById('id_sucursal');
    const tabla = document.querySelector('#tablaBicicletas tbody');
    const btnFinalizar = document.getElementById('btnFinalizar');
    const formPedido = document.getElementById('formPedido');
    const contadorBicis = document.getElementById('contadorBicis');
    
    // Modales
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    
    // Botones de modales
    const confirmAddBtn = document.getElementById('confirmAddBtn');
    
    let listaBicis = [];
    let currentBici = null;

    // Habilitar campo de búsqueda cuando se selecciona sucursal
    sucursalSelect.addEventListener('change', () => {
        numChasisInput.disabled = !sucursalSelect.value;
        if (sucursalSelect.value) {
            numChasisInput.focus();
        } else {
            numChasisInput.value = '';
        }
    });

    // Manejar entrada de búsqueda
    numChasisInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            await buscarBicicleta();
        }
    });

    // Confirmar agregar bicicleta
    confirmAddBtn.addEventListener('click', () => {
        if (currentBici) {
            // Verificar duplicados antes de agregar
            const existe = listaBicis.some(b => b.num_chasis === currentBici.num_chasis);
            if (!existe) {
                listaBicis.push(currentBici);
                renderizarTabla();
                numChasisInput.value = '';
                numChasisInput.focus();
            } else {
                mostrarErrorModal('Esta bicicleta ya fue agregada');
            }
            confirmModal.hide();
            currentBici = null;
        }
    });

    // Función para buscar bicicleta
    async function buscarBicicleta() {
        const numSerie = numChasisInput.value.trim();
        if (!numSerie) return;

        try {
            const response = await fetch(`/api/bicicletas/buscar?serie=${encodeURIComponent(numSerie)}`);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Error al buscar la bicicleta');
            }

            // Verificar si la bicicleta ya está en un pedido
            if (data.pedido_id) {
                throw new Error('Esta bicicleta ya está asignada a un pedido');
            }

            // Mostrar modal de confirmación
            currentBici = {
                num_chasis: data.num_chasis,
                modelo: data.modelo.nombre_modelo || 'N/D',
                color: data.color.nombre_color || 'N/D',
                id_bicicleta: data.id_bicicleta
            };

            document.getElementById('confirmModalBody').innerHTML = `
                <p>¿Agregar esta bicicleta al pedido?</p>
                <div class="card bg-light mt-2">
                    <div class="card-body p-2">
                        <p class="mb-1"><strong>Serie:</strong> ${data.num_chasis}</p>
                        <p class="mb-1"><strong>Modelo:</strong> ${currentBici.modelo}</p>
                        <p class="mb-0"><strong>Color:</strong> ${currentBici.color}</p>
                    </div>
                </div>
            `;
            confirmModal.show();

        } catch (error) {
            console.error('Error:', error);
            mostrarErrorModal(error.message);
            numChasisInput.value = '';
            numChasisInput.focus();
        }
    }

    // Renderizar tabla de bicicletas
    function renderizarTabla() {
        tabla.innerHTML = '';
        
        listaBicis.forEach((bici, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center">${index + 1}</td>
                <td class="text-center">${bici.num_chasis}</td>
                <td class="text-center">${bici.modelo}</td>
                <td class="text-center">${bici.color}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger" onclick="quitarBici(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tabla.appendChild(tr);
        });

        // Actualizar contador y estado del botón
        contadorBicis.textContent = listaBicis.length;
        btnFinalizar.disabled = listaBicis.length === 0;
    }

    // Función para quitar bicicleta
    window.quitarBici = function(index) {
        listaBicis.splice(index, 1);
        renderizarTabla();
    };

    // Manejar envío del formulario
    formPedido.addEventListener('submit', (e) => {
        if (listaBicis.length === 0) {
            e.preventDefault();
            mostrarErrorModal('Debes agregar al menos una bicicleta');
            return;
        }

        // Agregar bicicletas como input hidden
        const inputBicis = document.createElement('input');
        inputBicis.type = 'hidden';
        inputBicis.name = 'bicicletas';
        inputBicis.value = JSON.stringify(listaBicis.map(b => b.id_bicicleta));
        formPedido.appendChild(inputBicis);
    });

    // Funciones para mostrar modales
    function mostrarInfoModal(mensaje) {
        document.getElementById('infoModalBody').textContent = mensaje;
        infoModal.show();
    }

    function mostrarErrorModal(mensaje) {
        document.getElementById('errorModalBody').textContent = mensaje;
        errorModal.show();
    }

    // Evento submit del formulario
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
@endsection