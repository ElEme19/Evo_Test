@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h4 class="mb-4 text-center">Editar Modelo: {{ $modelo->nombre_modelo }}</h4>

            <form action="{{ route('modelos.update', $modelo->id_modelo) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 rounded-3">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID del Modelo</label>
                    <input type="text" class="form-control" value="{{ $modelo->id_modelo }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
                    <input type="text" name="nombre_modelo" class="form-control" required maxlength="15" value="{{ old('nombre_modelo', $modelo->nombre_modelo) }}">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" required maxlength="64">{{ old('descripcion', $modelo->descripcion) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="foto_modelo" class="form-label">Actualizar Foto (opcional)</label>
                    <input type="file" name="foto_modelo" class="form-control" accept="image/*">
                    @if($modelo->foto_modelo)
                        <div class="mt-2">
                            <img src="data:image/jpeg;base64,{{ base64_encode($modelo->foto_modelo) }}" width="80" class="rounded shadow-sm" />
                        </div>
                    @endif
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
