@extends ('layout.app')

@section('title')

@section ('conten')

    <div class="text-center my-2">
        <h3>
            Tipos de Lote 
            <span class="badge rounded-pill text-bg-success">Ver</span>
        </h3>
    </div>

    <div class="d-flex justify-content-center my-3">
        <form class="row g-3 justify-content-center">
            <div class="col-md-12">
                <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar por ID o fecha...">
            </div>
        </form>
    </div>




@if (session('success'))
    <div class="text-center">
        <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                fill="currentColor"
                class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="Warning:">
                 <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <small class="fw-semibold">  <!-- Para la alerta -->
                {{ session('success') }}  
            </small>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong>{{ session('error') }}</strong>
        </div>
    </div>
@endif



<table class="table mt-2" id="tablaTipoStock">
    <thead>
        <tr>
            <th scope="col">ID Lote</th>
            <th scope="col">Fecha</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lotes as $lote)
        <tr>
            <td>{{ $lote->id_lote }}</td>
            <td>{{ \Carbon\Carbon::parse($lote->fecha_produccion)->format('d-m-Y') }}</td>
            <td>
                <button type="button" 
                        class="btn btn-outline-success" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalActualizar{{ $lote->id_lote }}">
                    Actualizar
                </button>
                 @include('Lote.actualizar')
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputBuscar');
        const tabla = document.getElementById('tablaTipoStock').getElementsByTagName('tbody')[0];

        const normalizarTexto = (texto) => {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/\s+/g, " ")
                .trim();
        };

        input.addEventListener('input', () => {
            const filtro = normalizarTexto(input.value);
            const filas = tabla.querySelectorAll('tr');

            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('td');
                let coincide = false;

                celdas.forEach(celda => {
                    const textoCelda = normalizarTexto(celda.textContent);
                    if (textoCelda.includes(filtro)) {
                        coincide = true;
                    }
                });

                fila.style.display = coincide ? '' : 'none';
            });
        });
    });
</script>


@endsection
