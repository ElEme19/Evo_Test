@extends('layout.app')
@section('conten-wrapper')

           @if (isset($error))
    <div class="alert alert-danger d-flex align-items-center">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong>{{ $error }}</strong>
    </div>
@endif


<p>Idioma actual: {{ app()->getLocale() }}</p>

@endsection