@extends('layout.app')

@section('conten')
<div class="text-center my-4">
    <h3>@lang('Imprimir QR de YouTube')</h3>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('bicicleta.imprimirQR') }}">
    @csrf
    <div class="mb-3">
        <label for="printerId" class="form-label">@lang('Seleccionar impresora')</label>
        <input type="number" class="form-control" id="printerId" name="printerId" placeholder="ID de la impresora" required>
        <small class="form-text text-muted">@lang('Introduce el ID de tu impresora PrintNode.')</small>
    </div>
    <button type="submit" class="btn btn-success">@lang('Imprimir QR')</button>
</form>
@endsection
