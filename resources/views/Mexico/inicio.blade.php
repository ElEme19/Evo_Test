@extends('layout.Prueba')
@section('conten')

@php
    $user = Auth::guard('usuarios')->user();
@endphp

<div class="container">
    @if($user)
        <h1 class="mt-5 text-center">{{ $user->tipo_dia }}!, {{ $user->user_name }}  </h1>
        

        <div class="row justify-content-center mt-5">
            <div class="col-mb-8">
                <div class="alert alert-info text-center">
                    ¡Bienvenide a Evobike!
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-mb-8">
                <div class="alert alert-info text-center">
                    Tipo de usuario: {{ $user->tipo_texto }}
                </div>
            </div>
        </div>
    @else
        <p>No has iniciado sesión.</p>
    @endif
</div>

@endsection
