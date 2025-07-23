@extends('layout.app')

@section('conten-wrapper')
<div class="container py-5 text-center">
    <h3 class="text-success">¡Pedido confirmado con éxito!</h3>
    <p>ID Pedido: <strong>{{ $pedido->id_pedido }}</strong></p>
    <a href="{{ route('pedido.ver') }}" class="btn btn-success mt-3">Volver</a>
</div>
@endsection
