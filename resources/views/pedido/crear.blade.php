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
<select name="id_sucursal" class="form-select" required>
    <option value="">Seleccione una sucursal</option>
    @foreach($sucursales as $sucursal)
        <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre_sucursal }}</option>
    @endforeach
</select>
        </div>

        {{-- Escaneo --}}
        <div class="mb-3">
            <label for="codigo_bici" class="form-label">Escanea Bicicleta (N° de Serie)</label>
            <input type="text" id="codigo_bici" class="form-control" autocomplete="off" placeholder="Escanea o escribe el número de serie" {{ old('id_sucursal') ? '' : 'disabled' }}>
        </div>

        {{-- Lista dinámica --}}
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

        {{-- Input oculto para las bicis escaneadas --}}
        <input type="hidden" name="bicis_json" id="bicis_json">

        <div class="text-center">
            <button type="submit" class="btn btn-success" id="btnFinalizar" disabled>
                Finalizar Pedido
            </button>
        </div>
    </form>
</div>

{{-- Script --}}
<script>
    const sucursalSelect = document.getElementById('id_sucursal');
    const inputCodigo = document.getElementById('codigo_bici');
    const tabla = document.getElementById('tablaBicicletas').querySelector('tbody');
    const inputBicis = document.getElementById('bicis_json');
    const btnFinalizar = document.getElementById('btnFinalizar');

    let listaBicis = [];

    // Habilitar campo escaneo solo si se selecciona sucursal
    sucursalSelect.addEventListener('change', () => {
        inputCodigo.disabled = !sucursalSelect.value;
        inputCodigo.value = '';
        listaBicis = [];
        renderizarTabla();
        btnFinalizar.disabled = true;
        if (sucursalSelect.value) {
            inputCodigo.focus();
        }
    });

    inputCodigo.addEventListener('keypress', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const codigo = inputCodigo.value.trim();

            if (!codigo) return;

            // Validar si ya fue agregada
            if (listaBicis.find(b => b.num_chasis === codigo)) {
                alert('Bicicleta ya agregada.');
                inputCodigo.value = '';
                return;
            }

            try {
                // Corrige la URL para enviar query param
                let url = '';
if (codigo.length === 4 && /^\d+$/.test(codigo)) {
    url = `/bicicleta/buscar-por-ultimos4?ult4=${encodeURIComponent(codigo)}`;
} else {
    url = `/bicicleta/buscarC?num_chasis=${encodeURIComponent(codigo)}`;
}
const res = await fetch(url);

                const data = await res.json();

                if (data.status === 'ok' && data.bicicleta) {
                    // Asegura que el objeto bici tenga las propiedades necesarias
                    listaBicis.push({
                        num_chasis: data.bicicleta.num_chasis,
                        modelo: data.bicicleta.modelo.nombre_modelo,
                        color: data.bicicleta.color.nombre_color
                    });
                    renderizarTabla();
                    inputCodigo.value = '';
                    btnFinalizar.disabled = false;
                    inputCodigo.focus();
                } else {
                    alert('Bicicleta no encontrada.');
                    inputCodigo.value = '';
                }
            } catch (err) {
                alert('Error al buscar bicicleta.');
                inputCodigo.value = '';
            }
        }
    });

    function renderizarTabla() {
        tabla.innerHTML = '';
        listaBicis.forEach((bici, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${bici.num_chasis}</td>
                    <td>${bici.modelo}</td>
                    <td>${bici.color}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="quitarBici('${bici.num_chasis}')">Quitar</button>
                    </td>
                </tr>
            `;
            tabla.insertAdjacentHTML('beforeend', row);
        });

        // Actualizar input oculto con la lista serializada
        inputBicis.value = JSON.stringify(listaBicis);

        // Controlar estado del botón Finalizar
        btnFinalizar.disabled = listaBicis.length === 0;
    }

    function quitarBici(codigo) {
        listaBicis = listaBicis.filter(b => b.num_chasis !== codigo);
        renderizarTabla();
        inputCodigo.focus();
    }
</script>


@endsection
