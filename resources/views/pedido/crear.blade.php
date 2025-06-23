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
            const valor = numChasisInput.value.trim();
            if (valor.length === 4 || valor.length === 17) {
                await buscarBicicleta(valor);
            }
        }
    });

    numChasisInput.addEventListener('input', async () => {
        const valor = numChasisInput.value.trim();
        if (valor.length === 4 || valor.length === 17) {
            await buscarBicicleta(valor);
        }
    });

    function renderizarTabla() {
        tabla.innerHTML = '';
        listaBicis.forEach((bici, i) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${i + 1}</td>
                <td>${bici.num_chasis}</td>
                <td>${bici.modelo}</td>
                <td>${bici.color}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="quitarBici('${bici.num_chasis}')">Quitar</button></td>
            `;
            tabla.appendChild(tr);
        });
        btnFinalizar.disabled = listaBicis.length === 0;
    }

    window.quitarBici = function(num_chasis) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== num_chasis);
        renderizarTabla();
    };

    async function buscarBicicleta(numSerie) {
        const yaAgregada = listaBicis.some(b => b.num_chasis.toUpperCase() === numSerie.toUpperCase());
        if (yaAgregada) {
            mostrarModal('Esta bicicleta ya fue agregada al pedido.', 'warning');
            numChasisInput.value = '';
            return;
        }

        try {
            const url = numSerie.length === 4 
                ? `/Bicicleta/buscar-por-ultimos4?ult4=${encodeURIComponent(numSerie)}`
                : `/Bicicleta/buscarC?num_chasis=${encodeURIComponent(numSerie)}`;

            const res = await fetch(url);
            if (!res.ok) throw new Error('Error en la respuesta del servidor');
            const data = await res.json();

            const biciData = data.bicicleta || data.bici;
            if (!biciData) {
                mostrarModal('No se encontró ninguna bicicleta con ese número', 'error');
                return;
            }

            const necesitaColor = !biciData.color || !biciData.color.nombre_color;
            let colorHtml = `<strong>Color:</strong> ${biciData.color?.nombre_color || 'NULO'}`;

            if (necesitaColor) {
                const colores = await fetch(`/colores-por-modelo/${biciData.modelo?.id_modelo || biciData.id_modelo}`)
                    .then(r => r.json());

                const selectColor = `
                    <div class="col-md-6">
        <label for="id_color" class="form-label">Color (Disponible según modelo)</label>
        <select name="id_color" id="id_color" class="form-select" disabled required>
            <option value="">Seleccione un color</option>
        </select>
    </div>
                `;
                colorHtml = selectColor;
            }

            modalBody.innerHTML = `
                <div class="alert alert-success">
                    <strong>Bicicleta encontrada:</strong>
                    <ul class="mt-2 mb-3">
                        <li><strong>N° Serie:</strong> ${biciData.num_chasis}</li>
                        <li><strong>Modelo:</strong> ${biciData.modelo?.nombre_modelo || biciData.modelo}</li>
                        <li>${colorHtml}</li>
                    </ul>
                </div>
                <div class="text-center">
                    <button id="confirmAdd" class="btn btn-success me-2">Agregar</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            `;

            modal.show();

            document.getElementById('confirmAdd').onclick = () => {
                const colorFinal = necesitaColor 
                    ? document.getElementById('colorSelect').value || 'SIN COLOR'
                    : biciData.color?.nombre_color || biciData.color;

                agregarBicicleta({
                    num_chasis: biciData.num_chasis,
                    modelo: biciData.modelo?.nombre_modelo || biciData.modelo,
                    color: colorFinal
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
        modalBody.innerHTML = `<div class="alert ${alertClass} mb-0">${mensaje}</div>`;
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
