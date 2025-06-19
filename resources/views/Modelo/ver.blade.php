@extends('layout.app')

@section('conten')
<div class="text-center my-4">
    <h3>
        <span class="badge rounded-pill text-bg-primary">Modelos</span>
    </h3>
</div>

@if(session('success'))
    <div class="text-center">
        <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <small class="fw-semibold">{{ session('success') }}</small>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong>{{ session('error') }}</strong>
        </div>
    </div>
@endif

<div class="d-flex justify-content-center my-3">
    <form class="row g-3 justify-content-center">
        <div class="col-md-12">
            <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar modelo o ID...">
        </div>
    </form>
</div>

@if (auth()->user()->id == 0)
    <div class="text-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearModelo">Crear Nuevo Modelo</button>
    </div>
@endif

<table class="table table-bordered table-hover mt-3" id="tablaModelos">
    <thead class="table-light">
        <tr class="text-center">
            <th>ID Modelo</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($modelos as $modelo)
            <tr>
                <td class="text-center">{{ $modelo->id_modelo }}</td>
                <td class="text-center">{{ $modelo->nombre_modelo }}</td>
               
                <td class="text-center">
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $modelo->id_modelo }}">Editar</button>
                    <div class="modal fade" id="modalEditar{{ $modelo->id_modelo }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ route('Modelo.update', $modelo->id_modelo) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Modelo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="nombre_modelo" class="form-control" value="{{ $modelo->nombre_modelo }}" required>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label class="form-label">Nueva Foto</label>
                                        <input type="file" name="foto_modelo" class="form-control">
                                        @if ($modelo->foto_modelo)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($modelo->foto_modelo) }}" width="100" class="mt-2 rounded shadow-sm">
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No hay modelos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="modal fade" id="modalCrearModelo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('Modelo.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Crear Modelo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">ID Modelo</label>
                    <input type="text" name="id_modelo" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre_modelo" class="form-control" required>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto_modelo" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputBuscar');
        const tabla = document.getElementById('tablaModelos').getElementsByTagName('tbody')[0];

        const normalizarTexto = (texto) => {
            return texto.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "").replace(/\s+/g, " ").trim();
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
