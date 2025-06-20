@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Encabezado -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Áreas Registradas</span>
                    <span class="badge rounded-pill text-bg-primary">Ver</span>
                </h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <!-- Botón para crear nueva área (abre modal) -->
            <div class="text-center mb-3">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCrearArea">
                    <i class="bi bi-plus-circle me-1"></i> Crear Nueva Área
                </button>
            </div>

            <!-- Tabla de áreas -->
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center" id="tablaAreas">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Área</th>
                                    <th>Nombre Área</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($areas as $area)
                                <tr>
                                    <td>{{ $area->id_area }}</td>
                                    <td>{{ $area->nombre_area }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary btnEditarArea"
                                            data-id="{{ $area->id_area }}"
                                            data-nombre="{{ $area->nombre_area }}"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarArea">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>

                                        <form action="{{ route('area.eliminar', $area->id_area) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar esta área?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-muted py-4">
                                        No hay áreas registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $areas->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>




<!-- Scripts necesarios -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
    // Validación Bootstrap
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Llenar modal editar con datos del área seleccionada
    document.querySelectorAll('.btnEditarArea').forEach(button => {
        button.addEventListener('click', event => {
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');

            document.getElementById('edit_id_area').value = id;
            document.getElementById('edit_nombre_area').value = nombre;

            // Cambiar acción del formulario al actualizar
            const form = document.getElementById('formEditarArea');
            form.action = `/area/${id}`;
        });
    });
</script>

@endsection
