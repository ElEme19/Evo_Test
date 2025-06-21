@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Pedidos Registrados</h3>
                <a href="{{ route('pedido.crear') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i> Crear Nuevo Pedido
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Sucursal</th>
                                    <th>Cantidad de Bicicletas</th>
                                    <th>Fecha Envío</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pedidos as $pedidoGroup)
                                    <tr>
                                        <td>{{ $pedidoGroup->id_pedido }}</td>
                                        <td>{{ $pedidoGroup->sucursal->nombre_sucursal ?? 'N/A' }}</td>
                                        <td>{{ \App\Models\Pedidos::where('id_pedido', $pedidoGroup->id_pedido)->count() }}</td>
                                        <td>{{ $pedidoGroup->fecha_envio->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('pedido.pdf', $pedidoGroup->id_pedido) }}" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-filetype-pdf"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted py-4">No hay pedidos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $pedidos->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
@endsection
