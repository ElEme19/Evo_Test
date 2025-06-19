@extends('layout.app')

@section('title')

@section('conten-wrapper')
    <div class="text-center  mt-4 px-3">
        <h3>
            Sucursales 
            <span class="badge rounded-pill text-bg-success">Ver</span>
        </h3>
    </div>

    <div class="d-flex justify-content-center my-3">
        <form class="row g-3 justify-content-center" novalidate>
            <div class="col-md-12">
                <input
                    type="text"
                    id="inputBuscar"
                    class="form-control"
                    placeholder="Buscar por ID, nombre o localización..."
                >
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="text-center">
            <div
                class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm"
                role="alert"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16"
                    fill="currentColor"
                    class="bi me-2"
                    viewBox="0 0 16 16"
                    role="img"
                    aria-label="Warning:"
                >
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96
                           0L.165 13.233c-.457.778.091 1.767.98
                           1.767h13.713c.889 0 1.438-.99.98-
                           1.767L8.982 1.566zM8 5c.535 0
                           .954.462.9.995l-.35 3.507a.552.552
                           0 0 1-1.1 0L7.1 5.995A.905.905 0
                           0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1
                           0 0 1 0-2z"
                    />
                </svg>
                <small class="fw-semibold">
                    {{ session('success') }}
                </small>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="text-center">
            <div
                class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm"
                role="alert"
            >
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

     @if (auth()->user()->rol == 0)
                            <div class="text-center mb-3">
                                <a href="{{ route('Sucursal.crear') }}" class="btn btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    Crear Nueva Sucursal
                                </a>
                            </div>
            @endif

    <div class="container mt-4">
        <table class="table table-bordered table-hover mt-5" id="tablaBicicletas">
             <thead class="table-light">
            <tr>
                <th scope="col"  class="text-center">ID Sucursal</th>
                <th scope="col"  class="text-center">Cliente</th>
                <th scope="col"  class="text-center">Nombre</th>
                <th scope="col"  class="text-center">Localización</th>
                <th scope="col"  class="text-center">Fachada</th>
                <th scope="col"  class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sucursales as $sucursal)
                <tr>
                    <td class="text-center" >{{ $sucursal->id_sucursal }}</td>
                    <td class="text-center">{{ $sucursal->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td class="text-center" >{{ $sucursal->nombre_sucursal }}</td>
                    <td class="text-center" >{{ $sucursal->localizacion }}</td>
                    <td class="text-center" >
                        @if($sucursal->foto_fachada)
                                <a
                                   href="{{ route('sucursal.imagen', ['path' => $sucursal->foto_fachada]) }}"
                                    target="_blank"
                                    class="text-decoration-none"
                                >
                                    Ver imagen
                                </a>

                        @else
                            <span class="text-muted">Sin foto</span>
                        @endif
                    </td>
                    <td class="text-center" >
                        <button
                            type="button"
                            class="btn btn-outline-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizar{{ $sucursal->id_sucursal }}"
                        >
                            Actualizar
                        </button>

                        {{-- Si tienes un partial para el modal de actualización: --}}
                        {{-- @include('Sucursal.actualizar', ['sucursal' => $sucursal]) --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('inputBuscar');
            const tabla = document
                .getElementById('tablaSucursales')
                .getElementsByTagName('tbody')[0];

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
