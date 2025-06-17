@extends ('layout.app')
@section ('conten')

<div class="card" style="width: 40rem;">
  <div class="card-body">
    <h5 class="card-title">Añadir Pieza</h5>
    <form method="POST" action="{{route('piezas.store')}}">
        @csrf
        <div class="form-grup">
            <label for="nombre_pieza"> Nombre:</label>
            <input type="text" id="nombre_pieza" name="nombre_pieza" class="form-control" required>
        </div>
        <div class="form-grup">
            <label for="descripcion_pieza"> Descripcion Pieza:</label>
            <input type="text" id="descripcion_pieza" name="descripcion_pieza" mt-5 class="form-control" required>
        </div>
        <button type="submit" class="form-control"  >Guardar</button>
        
    </form>
    @if (session('success'))
    <div class="alert alert-success" role="alert" mt-5>
        {{session('success')}}
    </div>
    @endif

  </div>
</div>


@endsection 