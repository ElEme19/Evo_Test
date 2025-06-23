@extends('layout.app')

@section('conten')
<div class="container px-2 px-md-3 py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9 col-xl-8">

            <!-- Encabezado -->
            <header class="text-center mb-3">
                <h1 class="h5 fw-bold text-success">
                    <i class="bi bi-truck me-2"></i>Nuevo Pedido
                </h1>
                <div class="badge bg-success bg-opacity-10 text-success fs-6 px-2 py-1">
                    <i class="bi bi-info-circle me-1"></i>Escanea las bicicletas
                </div>
            </header>

            <!-- Alertas -->
            <div class="mb-3">
                @if (session('success'))
                    <div class="alert alert-success py-2 px-3 small" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger py-2 px-3 small" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('pedido.store') }}" id="formPedido" class="bg-white p-3 rounded-3 shadow-sm border border-success border-opacity-25">
                @csrf

                <!-- Sucursal -->
                <div class="mb-3">
                    <label for="id_sucursal" class="form-label fw-semibold text-success small">
                        <i class="bi bi-shop me-1"></i>Sucursal Destino
                    </label>
                    <select name="id_sucursal" id="id_sucursal" class="form-select form-select-sm border-success" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                        @endforeach
                    </select>
                    <div class="mt-1 small text-success fw-semibold" id="nombreSucursalSeleccionada"></div>
                </div>

                <!-- Escáner Bicicleta -->
                <div class="mb-3 p-2 bg-light border border-success rounded">
                    <label for="num_chasis" class="form-label text-success small">
                        <i class="bi bi-upc-scan me-1"></i>Escanea Bicicleta
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-success">
                            <i class="bi bi-upc-scan text-success"></i>
                        </span>
                        <input type="text" id="num_chasis" class="form-control border-success" 
                               autocomplete="off" placeholder="Serie completa o últimos 4" disabled>
                    </div>
                    <small class="text-muted"><i class="bi bi-keyboard"></i> Presiona Enter</small>
                </div>

                <!-- Tabla Bicicletas -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h6 fw-semibold text-success mb-0">
                            <i class="bi bi-list-check me-2"></i>Bicicletas
                        </h2>
                        <span class="badge bg-success text-white small" id="contadorBicis">0</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover align-middle" id="tablaBicicletas">
                            <thead class="table-success">
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

<!-- Modal -->
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success border-2">
            <div class="modal-header bg-success bg-opacity-10">
                <h5 class="modal-title text-success">
                    <i class="bi bi-search me-2"></i>Resultado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBodyMensaje"></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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