@extends('layout.app')

@section('conten')

<div class="container mt-4">
    <h3 class="text-center mb-3">
        Crear Nuevo Pedido
        <span class="badge bg-success">Nuevo</span>
    </h3>

    {{-- Errores --}}
    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Éxito --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- Formulario --}}
    <form method="POST" action="{{ route('pedido.store') }}" id="formPedido">
        @csrf

        {{-- Seleccionar Sucursal --}}
        <div class="mb-3">
            <label for="id_sucursal" class="form-label">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal" class="form-select" required>
                <option value="">Seleccione una sucursal</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
                @endforeach
            </select>
        </div>

        {{-- Escaneo --}}
        <div class="mb-3">
            <label for="codigo_bici" class="form-label">Escanea Bicicleta (N° de Serie o últimos 4 dígitos)</label>
            <input type="text" id="codigo_bici" class="form-control" autocomplete="off" placeholder="Escanea o escribe el número de serie o últimos 4 dígitos" disabled>
        </div>

        {{-- Tabla para mostrar bicis agregadas --}}
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

        {{-- Botón Finalizar --}}
        <div class="text-center">
            <button type="submit" class="btn btn-success" id="btnFinalizar" disabled>Finalizar Pedido</button>
        </div>
    </form>
</div>

{{-- Modal para mostrar resultado búsqueda número de serie --}}
<div class="modal fade" id="modalResultadoBusqueda" tabindex="-1" aria-labelledby="modalResultadoBusquedaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalResultadoBusquedaLabel">Resultado de la búsqueda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalBodyMensaje">
        <!-- Mensaje dinámico aquí -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputCodigo = document.getElementById('codigo_bici');
    const sucursalSelect = document.getElementById('id_sucursal');
    const tabla = document.getElementById('tablaBicicletas').querySelector('tbody');
    const modal = new bootstrap.Modal(document.getElementById('modalResultadoBusqueda'));
    const modalBody = document.getElementById('modalBodyMensaje');
    const btnFinalizar = document.getElementById('btnFinalizar');

    let listaBicis = [];

    // Inicialmente input deshabilitado
    inputCodigo.disabled = true;

    // Habilitar input solo cuando se selecciona sucursal
    sucursalSelect.addEventListener('change', () => {
        if (sucursalSelect.value) {
            inputCodigo.disabled = false;
            inputCodigo.focus();
        } else {
            inputCodigo.disabled = true;
            inputCodigo.value = '';
            listaBicis = [];
            renderizarTabla();
            btnFinalizar.disabled = true;
        }
    });

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

    window.quitarBici = function(num_chasis) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== num_chasis);
        renderizarTabla();
    }

    inputCodigo.addEventListener('keypress', async e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const valor = inputCodigo.value.trim();
            if (!valor) return;

            // Evitar duplicados
            if (listaBicis.some(b => b.num_chasis === valor)) {
                alert('Esta bicicleta ya fue agregada.');
                inputCodigo.value = '';
                return;
            }

            try {
                let url = '';
                // Si la longitud es 4, buscar por últimos 4 dígitos
                if (valor.length === 4) {
                    url = `/bicicleta/buscar-por-ultimos4?ult4=${encodeURIComponent(valor)}`;
                } else {
                    // Buscar por número de serie completo
                    url = `/bicicleta/buscarC?num_chasis=${encodeURIComponent(valor)}`;
                }

                const res = await fetch(url);
                const data = await res.json();

                if (data.status === 'ok' && data.bicicleta) {
                    const bici = data.bicicleta;

                    // Mostrar info en modal
                    modalBody.innerHTML = `
                        <p><strong>Bicicleta encontrada:</strong></p>
                        <ul>
                            <li><strong>Número de Serie:</strong> ${bici.num_chasis}</li>
                            <li><strong>Modelo:</strong> ${bici.modelo.nombre_modelo}</li>
                            <li><strong>Color:</strong> ${bici.color.nombre_color}</li>
                        </ul>
                        <p>¿Deseas agregar esta bicicleta al pedido?</p>
                        <button id="btnAgregarModal" class="btn btn-success">Agregar</button>
                    `;
                    modal.show();

                    // Agregar event listener al botón dentro del modal
                    document.getElementById('btnAgregarModal').onclick = () => {
                        listaBicis.push({
                            num_chasis: bici.num_chasis,
                            modelo: bici.modelo.nombre_modelo,
                            color: bici.color.nombre_color
                        });
                        renderizarTabla();
                        inputCodigo.value = '';
                        modal.hide();
                    };

                } else {
                    modalBody.innerHTML = `<p><strong>No se encontró ninguna bicicleta con ese número o últimos 4 dígitos.</strong></p>`;
                    modal.show();
                }
            } catch (error) {
                modalBody.innerHTML = `<p><strong>Error al realizar la búsqueda.</strong></p>`;
                modal.show();
            }
        }
    });
});
</script>

@endsection
