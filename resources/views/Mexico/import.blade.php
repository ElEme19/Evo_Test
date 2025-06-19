@extends('layout.app')

@section('conten-wrapper')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            
            <!-- Título mejorado -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Carga de chasis desde Excel</span>
                    <span class="badge rounded-pill text-bg-primary">Importar</span>
                </h3>
            </div>

            <!-- Alertas de sesión -->
            @if(session('status'))
                <div class="text-center">
                    <div class="alert alert-success d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
                        </svg>
                        <span class="fw-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Mostrar errores -->
            @if($errors->any())
                <div class="text-center">
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger d-inline-flex align-items-center py-2 px-3 rounded-3 shadow-sm mb-2" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 10.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                            </svg>
                            <span class="fw-semibold">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Tarjeta contenedora del formulario -->
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body">
                    <form action="{{ route('procesador.procesar') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-4">
                            <label for="archivo" class="form-label fw-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel me-1" viewBox="0 0 16 16">
                                    <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                </svg>
                                Seleccionar archivo Excel
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="archivo" 
                                   name="archivo"
                                   accept=".xls,.xlsx"
                                   required>
                            <div class="invalid-feedback">
                                Por favor selecciona un archivo Excel válido.
                            </div>
                            <small class="text-muted">Formatos aceptados: .xls, .xlsx</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload me-1" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                </svg>
                                Cargar y procesar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Instrucciones -->
            <div class="card shadow-sm rounded-3 border-0 mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle me-1" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        Instrucciones
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">1. El archivo debe estar en formato Excel (.xls o .xlsx)</li>
                        <li class="list-group-item">2. Asegúrate de que los datos estén en la primera hoja</li>
                        <li class="list-group-item">3. Verifica que el formato de los chasis sea correcto</li>
                        <li class="list-group-item">4. El sistema validará automáticamente los datos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Validación del formulario -->
<script>
// Ejemplo de validación Bootstrap
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endsection