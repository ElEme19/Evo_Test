@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h4 class="mb-4 text-center">Crear Nuevo Modelo</h4>

            <form action="{{ route('modelos.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 rounded-3">
                @csrf

                <div class="mb-3">
                    <label for="id_modelo" class="form-label">ID del Modelo</label>
                    <input type="text" name="id_modelo" class="form-control" required maxlength="64" value="{{ old('id_modelo') }}">
                </div>

                <div class="mb-3">
                    <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
                    <input type="text" name="nombre_modelo" class="form-control" required maxlength="15" value="{{ old('nombre_modelo') }}">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" required maxlength="64">{{ old('descripcion') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="foto_modelo" class="form-label">Foto (opcional)</label>
                    <input type="file" name="foto_modelo" class="form-control" accept="image/*">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
