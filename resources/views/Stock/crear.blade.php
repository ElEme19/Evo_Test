@extends('layout.app')

@section('title')

@section('conten')
    

<div class="text-center my-4">
    <h3>
        Stock 
        <span class="badge rounded-pill text-bg-success">Nuevo</span>
    </h3>
</div>

@if (session('success'))
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                fill="currentColor"
                class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="success:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <small class="fw-semibold">  <!-- Para la alerta -->
                {{ session('success') }}  
            </small>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong>{{ session('error') }}</strong>
        </div>
    </div>
@endif



<form id= "formulario-stock" class="row g-3 was-validated" method="POST" action="{{ route('Stock.store') }}">
    <!-- @section('scripts')
    <script>
            document.addEventListener("DOMContentLoaded", function()
        {
            const form= document.getElementById("formulario-stock");

            form.addEventListener("keydown", function(event){
                if (event.key === "Enter"){
                    event.preventDefault();
                }
            });
        });
        </script>
        @endsection -->

    @csrf
    <div class="col-md-6">
        <label for="id_tipoStock" class="form-label">ID Tipo Stock</label>
        <input type="text" class="form-control" id="id_tipoStock" value="{{ $nextId }}" disabled>
    </div>

    <div class="col-md-6">
        <label for="nombre_stock" class="form-label">Nombre del Tipo de Stock</label>
        <input type="text" class="form-control is-valid" id="nombre_stock" name="nombre_stock" placeholder="Descripción del tipo de stock" required>
        <div class="invalid-feedback">Ingrese un nombre.</div>
    </div>

    

        <div class="row mb-3 align-items-center mt-4">
    <div class="col text-start">
        <button class="btn btn-outline-success" type="submit">
            Guardar Tipo de Stock
        </button>
    </div>
    <div class="col text-end">
        <a href="{{ route('Stock.ver') }}" class="btn btn-outline-success" >
            Ver Tipo de Stock
        </a>
    </div>
</div>


</form>

@endsection
