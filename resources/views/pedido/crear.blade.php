@extends('layout.app')

@section('conten')

<div class="container mt-4">
    <h3 class="text-center mb-3">
        Crear Nuevo Pedido
        <span class="badge bg-success">Nuevo</span>
    </h3>

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('pedido.store') }}" id="formPedido">
        @csrf

        <div class="mb-3">
            <label for="id_sucursal" class="form-label">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal" class="form-select" required>
                <option value="">Seleccione una sucursal</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="num_chasis" class="form-label">Escanea Bicicleta (N° de Serie o últimos 4 dígitos)</label>
            <input type="text" id="num_chasis" class="form-control" autocomplete="off" placeholder="Escanea o escribe el número de serie o últimos 4 dígitos" disabled>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-bordered text-center align-middle" id="tablaBicicletas">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Número de Serie</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success" id="btnFinalizar" disabled>Finalizar Pedido</button>
        </div>
    </form>
</div>

<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-labelledby="modalResultadoBusquedaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalResultadoBusquedaLabel">Resultado de la búsqueda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalBodyMensaje"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
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

    let listaBicis = [];

    numChasisInput.disabled = true;

    // Habilitar campo de búsqueda cuando se selecciona sucursal
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

    // Renderizar la tabla con las bicicletas agregadas
    function renderizarTabla() {
        tabla.innerHTML = '';
        listaBicis.forEach((bici, i) => {
            tabla.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${i + 1}</td>
                    <td>${bici.num_chasis}</td>
                    <td>${bici.modelo}</td>
                    <td>${bici.color}</td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="quitarBici('${bici.num_chasis}')">Quitar</button></td>
                </tr>
            `);
        });
        btnFinalizar.disabled = listaBicis.length === 0;
    }

    // Función global para quitar bicicletas
    window.quitarBici = function(num_chasis) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== num_chasis);
        renderizarTabla();
    }

    // Manejar la búsqueda al presionar Enter
    numChasisInput.addEventListener('keydown', async e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const numSerie = numChasisInput.value.trim();
            if (!numSerie) return;

            // Verificar si ya está agregada
            if (listaBicis.some(b => b.num_chasis === numSerie)) {
                modalBody.innerHTML = '<div class="alert alert-warning">Esta bicicleta ya fue agregada al pedido.</div>';
                modal.show();
                numChasisInput.value = '';
                return;
            }

            try {
                let url = '';
                if (numSerie.length === 4) {
                    url = `/bicicleta/buscar-por-ultimos4?ult4=${encodeURIComponent(numSerie)}`;
                } else {
                    url = `/bicicleta/buscarC?num_chasis=${encodeURIComponent(numSerie)}`;
                }

                const res = await fetch(url);
                const data = await res.json();

                if (data.bicicleta || data.bici) { // Compatible con ambas respuestas
                    const bici = data.bicicleta || data.bici;

                    if (!bici) {
                        throw new Error('Bicicleta no encontrada');
                    }

                    modalBody.innerHTML = `
                        <p><strong>Bicicleta encontrada:</strong></p>
                        <ul>
                            <li><strong>Número de Serie:</strong> ${bici.num_chasis}</li>
                            <li><strong>Modelo:</strong> ${bici.modelo?.nombre_modelo || bici.modelo}</li>
                            <li><strong>Color:</strong> ${bici.color?.nombre_color || bici.color}</li>
                        </ul>
                        <p>¿Deseas agregar esta bicicleta al pedido?</p>
                        <button id="btnAgregarModal" class="btn btn-success">Agregar</button>
                    `;
                    modal.show();

                    document.getElementById('btnAgregarModal').onclick = () => {
                        listaBicis.push({
                            num_chasis: bici.num_chasis,
                            modelo: bici.modelo?.nombre_modelo || bici.modelo,
                            color: bici.color?.nombre_color || bici.color
                        });
                        renderizarTabla();
                        numChasisInput.value = '';
                        modal.hide();
                    };
                } else {
                    modalBody.innerHTML = `<div class="alert alert-danger">No se encontró ninguna bicicleta con ese número o últimos 4 dígitos.</div>`;
                    modal.show();
                }
            } catch (error) {
                console.error('Error en la búsqueda:', error);
                modalBody.innerHTML = `<div class="alert alert-danger">Error al realizar la búsqueda: ${error.message}</div>`;
                modal.show();
            }
        }
    });

    // Manejar el envío del formulario
    formPedido.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Crear input hidden con las bicicletas en JSON
        const bicisInput = document.createElement('input');
        bicisInput.type = 'hidden';
        bicisInput.name = 'bicis_json';
        bicisInput.value = JSON.stringify(listaBicis);
        formPedido.appendChild(bicisInput);
        
        // Enviar formulario
        formPedido.submit();
    });
});
</script>

@endsection
