@extends('layout.app')



@section('conten')

<div class="text-center my-4">
    <h3>
        
        <span class="badge rounded-pill text-bg-success">Ver</span>
    </h3>
</div>

@if (session('success'))
    <div class="text-center">
        <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                fill="currentColor"
                class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="success:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
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

<div class="d-flex justify-content-center my-3">
    <form class="row g-3 justify-content-center">
        <div class="col-md-12">
            <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar membresía o ID...">
        </div>
    </form>
</div>

@if (auth()->user()->id == 0)
    <div class="text-center mb-3">
        <a href="{{ route('Membresia.create') }}" class="btn btn-primary">Crear Nueva Membresía</a>
    </div>
@endif

<table class="table table-bordered table-hover mt-3" id="tablaMembresias">
    <thead class="table-light">
        <tr class="text-center">
            <th>ID Membresía</th>
            <th>Descripción General</th>
             <th>NO TOCAR</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($membresias as $m)
            <tr>
                <td class="text-center">{{ $m->id_membresia }}</td>
                <td class="text-center">{{ $m->descripcion_general }}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar{{ $m->id_membresia }}">
                        Actualizar
                    </button>
                    @include('Membresia.actualizar', ['m' => $m])
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No hay membresías registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputBuscar');
        const tabla = document.getElementById('tablaMembresias').getElementsByTagName('tbody')[0];

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
