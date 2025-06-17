@extends('layout/app2')
@section ('fondo')

<form method="POST" action="{{route('login')}}" >
    @csrf

                    <div class="d-flex align-items-center mb-5 pb-3">
                    <img src="images/logo.webp" alt="Logo" style="max-width: 350px; max-height: 130px; width: 100%;">

                    </div>
                    @if ($errors->any())
              <div class="alert alert-danger mt-1">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
              </div>
              @endif


                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; ">Inicio de sesion</h5>

                  <div class="form-outline mb-4">
                    <input type="text" name="user_name" id="form2Example17" class="form-control form-control-lg"  placeholder="user@evobike.com" />
                        <label class="form-label" for="form2Example17">Correo electrónico</label>
                            </div>

                                <div class="form-outline mb-4">
                            <input type="password" name="user_pass" id="form2Example27" class="form-control form-control-lg" placeholder="*********" />
                                <label class="form-label" for="form2Example27">Contraseña</label>
                                        </div>
                        

                  <div class="pt-1 mb-4">
                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-lg btn-block" 
                        type="submit" style="background-color: #4DB53F; border-color:rgb(255, 255, 255);">
                         Entrar
                        </button>

                        <div class="text-center mt-3">
   
</div>

                  </div>

                </form>

                


@endsection