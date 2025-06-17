@extends ('layout.app')
@section ('conten')

<h1>Listado de Piezas </h1>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Id Pieza</th>
      <th scope="col">Nombre</th>
      <th scope="col">Descripcion</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    
    <tr>
    @foreach($piezas as $pieza)
  <tr>
    <td>{{ $pieza->id_piezas }}</td>
    <td>{{ $pieza->nombre_pieza }}</td>
    <td>{{ $pieza->descripcion_pieza }}</td>
    <td>
      <button type="button" 
              class="btn btn-outline-success" 
              data-bs-toggle="modal" 
              data-bs-target="#editModal{{ $pieza->id_piezas}}">
        Actualiza
      </button>
      @include('piezas.actualizar')
    </td>
  </tr>
@endforeach
  </tbody>
</table>
@if (session('success'))
    <div class="alert alert-success" role="alert" mt-5>
        {{session('success')}}
    </div>
    @endif


@endsection 


