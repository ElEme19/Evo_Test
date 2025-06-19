@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">

            <!-- Título -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Modelos</span>
                    <span class="badge rounded-pill text-bg-primary">Ver</span>
                </h3>
            </div>

            <!-- Alertas -->
            @if (session('success'))
                <div class="text-center">
                    <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <strong class="me-2">✔</strong>
                        <small class="fw-semibold">{{ session('success') }}</small>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="text-center">
                    <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <strong class="me-2">✖</strong>
                        <small class="fw-semibold">{{ session('error') }}</small>
                    </div>
                </div>
            @endif

            <!-- Buscador -->
            <div class="d-flex justify-content-center my-3">
                <form class="row g-3 justify-content-center w-100" method="GET" action="{{ route('modelos.ver') }}">
                    <div class="col-md-8 position-relative">
                        <input type="text" name="q" value="{{ $q }}" class="form-control ps-5" placeholder="Buscar modelo...">
                        <span class="position-absolute start-0 top-50 translate-middle-y ps-3">
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                </form>
            </div>

            <!-- Crear modelo -->
            @if (auth()->user()->rol == 0)
                <div class="text-center mb-3">
                    <a href="{{ route('modelos.crear') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-1"></i> Crear Nuevo Modelo
                    </a>
                </div>
            @endif

            <!-- Tabla -->
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Imagen</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($modelos as $modelo)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $modelo->id_modelo }}</td>
                                        <td class="text-center fw-semibold">{{ $modelo->nombre_modelo }}</td>
                                        <td class="text-center">{{ $modelo->descripcion }}</td>
                                        <td class="text-center">
                                            @if($modelo->foto_modelo)
                                                <img src="data:image/jpeg;base64,{{ base64_encode($modelo->foto_modelo) }}" width="60" class="rounded shadow-sm" />
                                            @else
                                                <span class="text-muted">Sin imagen</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('modelos.editar', $modelo->id_modelo) }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                            <form action="{{ route('modelos.eliminar', $modelo->id_modelo) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este modelo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-exclamation-circle me-2"></i> No hay modelos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
