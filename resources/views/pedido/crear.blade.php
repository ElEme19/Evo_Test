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

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const numChasisInput = document.getElementById('num_chasis');
    const sucursalSelect = document.getElementById('id_sucursal');
    const tabla = document.querySelector('#tablaBicicletas tbody');
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
            mostrarAlerta('Esta bicicleta ya fue agregada al pedido.', 'warning');
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
                    <span class="badge bg-light text-dark border">${bici.color}</span>
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
                mostrarAlerta('No se encontró ninguna bicicleta con ese número', 'error');
                return;
            }

            // Verificar si ya tiene pedido asociado
            if (biciData.pedido_asociado) {
                mostrarAlerta('Esta bicicleta ya tiene un pedido registrado y no puede agregarse.', 'warning');
                numChasisInput.value = '';
                return;
            }

            // Verificar duplicados
            const yaExiste = listaBicis.some(b => b.num_chasis.toUpperCase() === biciData.num_chasis.toUpperCase());
            if (yaExiste) {
                mostrarAlerta('Esta bicicleta ya fue agregada al pedido.', 'warning');
                numChasisInput.value = '';
                return;
            }

            const modelo = biciData.modelo?.nombre_modelo || biciData.modelo || 'N/D';
            const color = biciData.color?.nombre_color || biciData.color || 'N/D';

            if (confirm(`¿Agregar bicicleta?\n\nN° Serie: ${biciData.num_chasis}\nModelo: ${modelo}\nColor: ${color}`)) {
                agregarBicicleta({
                    num_chasis: biciData.num_chasis,
                    modelo: modelo,
                    color: color
                });
            }

        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('Error al buscar la bicicleta: ' + error.message, 'error');
        }
    }

    function agregarBicicleta(bici) {
        listaBicis.push(bici);
        renderizarTabla();
        numChasisInput.value = '';
        numChasisInput.focus();
    }

    function mostrarAlerta(mensaje, tipo = 'info') {
        const alertClass = {
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'success': 'alert-success',
            'info': 'alert-info'
        }[tipo];
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} mb-3`;
        alertDiv.innerHTML = `
            <i class="bi ${tipo === 'error' ? 'bi-exclamation-octagon-fill' : 
                          tipo === 'warning' ? 'bi-exclamation-triangle-fill' : 
                          tipo === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'} 
                me-2"></i>
            ${mensaje}
        `;
        
        // Insertar al principio del contenedor del formulario
        formPedido.insertBefore(alertDiv, formPedido.firstChild);
        
        // Eliminar después de 5 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    formPedido.addEventListener('submit', (e) => {
        e.preventDefault();
        if (listaBicis.length === 0) {
            mostrarAlerta('Debe agregar al menos una bicicleta al pedido', 'warning');
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