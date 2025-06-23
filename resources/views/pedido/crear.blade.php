@extends('layout.app')

@section('conten')
<div class="container px-0 px-md-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <!-- Encabezado -->
            <header class="text-center mb-4">
                <h1 class="h3 fw-bold text-success">
                    <i class="bi bi-cart-plus me-2"></i>Nuevo Pedido
                </h1>
                <div class="badge bg-success bg-opacity-10 text-success fs-6 fw-normal px-3 py-2">
                    <i class="bi bi-info-circle me-1"></i>Escanea las bicicletas para el pedido
                </div>
            </header>

            <!-- Alertas -->
            <div class="alert-container mb-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('pedido.store') }}" id="formPedido" class="bg-white p-4 rounded-3 shadow-sm">
                @csrf

                <!-- Selección de Sucursal -->
                <div class="mb-5">
                    <label for="id_sucursal" class="form-label fw-semibold">
                        <i class="bi bi-shop me-1"></i>Sucursal Destino
                    </label>
                    <select name="id_sucursal" id="id_sucursal" class="form-select form-select-lg" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Escáner de Bicicletas -->
                <div class="mb-4">
                    <label for="num_chasis" class="form-label fw-semibold">
                        <i class="bi bi-upc-scan me-1"></i>Escanea Bicicleta
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="num_chasis" class="form-control form-control-lg" 
                               autocomplete="off" 
                               placeholder="N° de Serie completo o últimos 4 dígitos"
                               disabled>
                    </div>
                    <small class="text-muted">Presiona Enter después de escanear/escribir</small>
                </div>

                <!-- Tabla de Bicicletas -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h6 fw-semibold text-muted mb-0">
                            <i class="bi bi-bicycle me-2"></i>Bicicletas en el pedido
                        </h2>
                        <span class="badge bg-success rounded-pill fs-6" id="contadorBicis">0</span>
                    </div>
                    
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" id="tablaBicicletas">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50px">#</th>
                                    <th>N° Serie</th>
                                    <th>Modelo</th>
                                    <th>Color</th>
                                    <th width="100px" class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4 py-2 shadow-sm" id="btnFinalizar" disabled>
                        <i class="bi bi-check-circle me-2"></i>Finalizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Resultados -->
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-search me-2"></i>Resultado de búsqueda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body py-4" id="modalBodyMensaje"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const numChasisInput = document.getElementById('num_chasis');
    const sucursalSelect = document.getElementById('id_sucursal');
    const tabla = document.querySelector('#tablaBicicletas tbody');
    const modal = new bootstrap.Modal(document.getElementById('modalResultadoBusqueda'));
    const modalBody = document.getElementById('modalBodyMensaje');
    const btnFinalizar = document.getElementById('btnFinalizar');
    const formPedido = document.getElementById('formPedido');
    const contadorBicis = document.getElementById('contadorBicis');

    let listaBicis = [];

    sucursalSelect.addEventListener('change', () => {
        if (sucursalSelect.value) {
            numChasisInput.disabled = false;
            numChasisInput.focus();
        } else {
            numChasisInput.disabled = true;
            numChasisInput.value = '';
            listaBicis = [];
            renderizarTabla();
            btnFinalizar.disabled = true;
        }
    });

    numChasisInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            await procesarBusqueda();
        }
    });

    numChasisInput.addEventListener('input', async () => {
        const valor = numChasisInput.value.trim().toUpperCase();
        if (valor.length === 4 || valor.length === 17) {
            await procesarBusqueda();
        }
    });

    async function procesarBusqueda() {
        const valor = numChasisInput.value.trim().toUpperCase();
        if (!valor) return;

        if (listaBicis.some(b => b.num_chasis.toUpperCase() === valor)) {
            mostrarModal('Esta bicicleta ya fue agregada al pedido.', 'warning');
            numChasisInput.value = '';
            return;
        }

        await buscarBicicleta(valor);
    }

    function renderizarTabla() {
        tabla.innerHTML = '';
        const repeticiones = {};

        // Contar repeticiones
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
                <td>${i + 1}</td>
                <td class="fw-semibold">${bici.num_chasis}</td>
                <td>${bici.modelo}</td>
                <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">${bici.color}</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                            onclick="quitarBici('${bici.num_chasis}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tabla.appendChild(tr);
        });
        
        btnFinalizar.disabled = listaBicis.length === 0;
        contadorBicis.textContent = listaBicis.length;
    }

    window.quitarBici = function(num_chasis) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== num_chasis);
        renderizarTabla();
    };

    async function buscarBicicleta(numSerie) {
        try {
            const url = numSerie.length === 4 
                ? `/Bicicleta/buscar-por-ultimos4?ult4=${encodeURIComponent(numSerie)}`
                : `/Bicicleta/buscarC?num_chasis=${encodeURIComponent(numSerie)}`;

            const res = await fetch(url);
            if (!res.ok) throw new Error('Error en la respuesta del servidor');

            const data = await res.json();
            const biciData = data.bicicleta || data.bici;

            if (!biciData || !biciData.num_chasis) {
                mostrarModal('No se encontró ninguna bicicleta con ese número', 'error');
                return;
            }

            // Verificar si ya tiene pedido asociado
            if (biciData.pedido_asociado) {
                mostrarModal('Esta bicicleta ya tiene un pedido registrado y no puede agregarse.', 'warning');
                numChasisInput.value = '';
                return;
            }

            // Verificar duplicados
            const yaExiste = listaBicis.some(b => b.num_chasis.toUpperCase() === biciData.num_chasis.toUpperCase());
            if (yaExiste) {
                mostrarModal('Esta bicicleta ya fue agregada al pedido.', 'warning');
                numChasisInput.value = '';
                return;
            }

            const modelo = biciData.modelo?.nombre_modelo || biciData.modelo || 'N/D';
            const color = biciData.color?.nombre_color || biciData.color || 'N/D';

            modalBody.innerHTML = `
                <div class="alert alert-success mb-0">
                    <div class="d-flex">
                        <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="alert-heading">¡Bicicleta encontrada!</h5>
                            <hr>
                            <div class="row g-2">
                                <div class="col-12">
                                    <span class="fw-semibold">N° Serie:</span> 
                                    <span class="badge bg-success bg-opacity-10 text-success">${biciData.num_chasis}</span>
                                </div>
                                <div class="col-12">
                                    <span class="fw-semibold">Modelo:</span> ${modelo}
                                </div>
                                <div class="col-12">
                                    <span class="fw-semibold">Color:</span> ${color}
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="confirmAdd" class="btn btn-success">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            modal.show();

            document.getElementById('confirmAdd').onclick = () => {
                agregarBicicleta({
                    num_chasis: biciData.num_chasis,
                    modelo: modelo,
                    color: color
                });
                modal.hide();
            };

        } catch (error) {
            console.error('Error:', error);
            mostrarModal('Error al buscar la bicicleta: ' + error.message, 'error');
        }
    }

    function agregarBicicleta(bici) {
        listaBicis.push(bici);
        renderizarTabla();
        numChasisInput.value = '';
        numChasisInput.focus();
    }

    function mostrarModal(mensaje, tipo = 'info') {
        const alertClass = {
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'success': 'alert-success',
            'info': 'alert-info'
        }[tipo];
        modalBody.innerHTML = `
            <div class="alert ${alertClass} mb-0">
                <i class="bi ${tipo === 'error' ? 'bi-exclamation-octagon-fill' : 
                              tipo === 'warning' ? 'bi-exclamation-triangle-fill' : 
                              tipo === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'} 
                    me-2"></i>
                ${mensaje}
            </div>
        `;
        modal.show();
    }

    formPedido.addEventListener('submit', (e) => {
        e.preventDefault();
        if (listaBicis.length === 0) {
            mostrarModal('Debe agregar al menos una bicicleta al pedido', 'warning');
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