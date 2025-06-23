@extends('layout.app')

@section('conten')
<div class="container px-0 px-md-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <!-- Encabezado -->
            <header class="text-center mb-4">
                <h1 class="h2 fw-bold text-success">
                    <i class="bi bi-truck me-2"></i>Nuevo Pedido de Bicicletas
                </h1>
                <div class="badge bg-success bg-opacity-10 text-success fs-5 fw-normal px-3 py-2">
                    <i class="bi bi-info-circle me-1"></i>Escanea las bicicletas para generar el pedido
                </div>
            </header>

            <!-- Alertas personalizadas -->
            <div class="mb-4">
                @if (session('success'))
                    <div class="text-center">
                        <div class="alert alert-success d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm border border-success border-opacity-25" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            <span class="fw-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="text-center">
                        <div class="alert alert-danger d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm border border-danger border-opacity-25" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <span class="fw-semibold">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('pedido.store') }}" id="formPedido" class="bg-white p-4 rounded-3 shadow-sm border border-success border-opacity-25">
                @csrf

                <!-- Selección de Sucursal - Versión Grande -->
                <div class="mb-4">
                    <label for="id_sucursal" class="form-label fw-semibold fs-5 text-success">
                        <i class="bi bi-shop me-2"></i>Sucursal Destino
                    </label>
                    <select name="id_sucursal" id="id_sucursal" class="form-select form-select-lg border-success border-opacity-50" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2 fs-4 fw-bold text-success" id="nombreSucursalSeleccionada">
                        <!-- Aquí se mostrará el nombre de la sucursal seleccionada -->
                    </div>
                </div>

                <!-- Escáner de Bicicletas - Versión Grande -->
                <div class="mb-4 bg-success bg-opacity-05 p-4 rounded-3">
                    <label for="num_chasis" class="form-label fw-semibold fs-5 text-success">
                        <i class="bi bi-upc-scan me-2"></i>Escanea Bicicleta
                    </label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-success bg-opacity-10 border-success border-opacity-50">
                            <i class="bi bi-upc-scan text-success"></i>
                        </span>
                        <input type="text" id="num_chasis" class="form-control form-control-lg border-success border-opacity-50" 
                               autocomplete="off" 
                               placeholder="N° de Serie completo o últimos 4 dígitos"
                               disabled>
                    </div>
                    <div class="mt-2 text-muted fs-6">
                        <i class="bi bi-keyboard-fill text-success"></i> Presiona Enter después de escanear/escribir
                    </div>
                </div>

                <!-- Tabla de Bicicletas -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-semibold text-success mb-0">
                            <i class="bi bi-list-check me-2"></i>Bicicletas en el pedido
                            <span class="badge bg-success rounded-pill fs-6 ms-2" id="contadorBicis">0</span>
                        </h2>
                    </div>
                    
                    <div class="table-responsive rounded-3 border border-success border-opacity-25">
                        <table class="table table-hover align-middle mb-0" id="tablaBicicletas">
                            <thead class="bg-success bg-opacity-10">
                                <tr>
                                    <th width="60px" class="fs-5">#</th>
                                    <th class="fs-5">N° Serie</th>
                                    <th class="fs-5">Modelo</th>
                                    <th class="fs-5">Color</th>
                                    <th width="100px" class="text-end fs-5">Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5 py-3 fs-4 shadow-sm" id="btnFinalizar" disabled>
                        <i class="bi bi-check2-circle me-2"></i>Finalizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Resultados -->
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success border-2">
            <div class="modal-header bg-success bg-opacity-10">
                <h5 class="modal-title text-success">
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
    const nombreSucursalDisplay = document.getElementById('nombreSucursalSeleccionada');
    const tabla = document.querySelector('#tablaBicicletas tbody');
    const modal = new bootstrap.Modal(document.getElementById('modalResultadoBusqueda'));
    const modalBody = document.getElementById('modalBodyMensaje');
    const btnFinalizar = document.getElementById('btnFinalizar');
    const formPedido = document.getElementById('formPedido');
    const contadorBicis = document.getElementById('contadorBicis');

    let listaBicis = [];

    // Mostrar nombre de sucursal seleccionada
    sucursalSelect.addEventListener('change', () => {
        if (sucursalSelect.value) {
            const selectedOption = sucursalSelect.options[sucursalSelect.selectedIndex];
            nombreSucursalDisplay.textContent = selectedOption.text;
            nombreSucursalDisplay.classList.add('animate__animated', 'animate__fadeIn');
            
            numChasisInput.disabled = false;
            numChasisInput.focus();
            
            // Remover animación después de que termine
            setTimeout(() => {
                nombreSucursalDisplay.classList.remove('animate__animated', 'animate__fadeIn');
            }, 1000);
        } else {
            nombreSucursalDisplay.textContent = '';
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
                <td class="fs-5">${i + 1}</td>
                <td class="fs-5 fw-semibold">${bici.num_chasis}</td>
                <td class="fs-5">${bici.modelo}</td>
                <td class="fs-5">
                    <span class="badge bg-success bg-opacity-10 text-success fs-6">${bici.color}</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill fs-5" 
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

            if (biciData.pedido_asociado) {
                mostrarModal('Esta bicicleta ya tiene un pedido registrado y no puede agregarse.', 'warning');
                numChasisInput.value = '';
                return;
            }

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
                        <i class="bi bi-bicycle fs-1 me-3 text-success"></i>
                        <div>
                            <h4 class="alert-heading text-success">¡Bicicleta encontrada!</h4>
                            <hr>
                            <div class="row g-2 fs-5">
                                <div class="col-12">
                                    <span class="fw-semibold">N° Serie:</span> 
                                    <span class="badge bg-success text-white">${biciData.num_chasis}</span>
                                </div>
                                <div class="col-12">
                                    <span class="fw-semibold">Modelo:</span> ${modelo}
                                </div>
                                <div class="col-12">
                                    <span class="fw-semibold">Color:</span> ${color}
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button class="btn btn-outline-success btn-lg" data-bs-dismiss="modal">Cancelar</button>
                                <button id="confirmAdd" class="btn btn-success btn-lg">
                                    <i class="bi bi-plus-circle me-1"></i> Agregar
                                </button>
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
        
        // Animación de confirmación
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        Toast.fire({
            icon: 'success',
            title: 'Bicicleta agregada',
            background: 'var(--bs-success-bg-subtle)',
            iconColor: 'var(--bs-success)'
        });
    }

    function mostrarModal(mensaje, tipo = 'info') {
        const alertClass = {
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'success': 'alert-success',
            'info': 'alert-info'
        }[tipo];
        
        const iconClass = {
            'error': 'bi-exclamation-octagon-fill',
            'warning': 'bi-exclamation-triangle-fill',
            'success': 'bi-check-circle-fill',
            'info': 'bi-info-circle-fill'
        }[tipo];
        
        modalBody.innerHTML = `
            <div class="alert ${alertClass} mb-0 fs-5">
                <i class="bi ${iconClass} me-2 fs-3"></i>
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

<!-- Incluir animaciones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Incluir SweetAlert2 para notificaciones -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection