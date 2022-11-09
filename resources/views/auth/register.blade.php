@extends('layouts.app')

@section('content')
<div class="cart-table-area section-padding-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-12">

                <main class="container-fluid w-100" role="main">
                    <div class="row">
                        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-white mnh-100vh">
                    

                            <div class="u-login-form">
                                <form class="mb-3" method="POST" action="{{ route('register') }}">
                                    {{ csrf_field() }}
                                    <div class="mb-3">
                                        <br>
                                        <h1 class="h2">Crea tu cuenta </h1>
                                        <p class="small">Llena con tus datos el formulario siguiente y presiona el boton de Registrarse.</p>
                                    </div>

                                    <div class="form-group mb-4{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name">Tu Nombre</label>
                                        <input id="name" class="form-control" name="name" type="text" placeholder="Juan Perez" value="{{ old('name') }}" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group mb-4{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="telefono">Tu Telefono / Whatsapp</label>
                                        <input id="telefono" class="form-control" name="telefono" type="text" placeholder="Tu Telefono/Whatsapp" value="{{ old('name') }}" required>

                                        @if ($errors->has('telefono'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('telefono') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">Tu email</label>
                                        <input id="email" class="form-control" name="email" type="email" placeholder="john.doe@example.com" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                        
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label for="password">Password</label>
                                                <input id="password" class="form-control" name="password" type="password" placeholder="ingresa tu password..." required>
                                            </div>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="confirmPassword">Confirmar password</label>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Reescribe tu password..." required>
                                            </div>

                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <!--Campos escondidos-->
                                    <input name="empresa" type="hidden" value="">
                                    <input name="moneda" type="hidden" value="Q.">
                                    <input name="zona_horaria" type="hidden" value="America/Guatemala">

                                    <button class="btn btn-info btn-block" type="submit"><i class="glyphicon glyphicon-ok"></i> Registrarse</button>
                                </form>

                                <p class="small">
                                    Ya tienes cuenta? <a href="{{ route('login') }}">Entra aqui</a>
                                </p>
                            </div>

                            <div class="u-login-form text-muted py-3 mt-auto">
                                <small> Si no puedes entrar a tu cuenta <a href="{{ url('/vistas/vcontacto') }}">contactanos</a>.</small>
                            </div>
                        </div>

                        <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center bg-light">
                            <img class="img-fluid position-relative u-z-index-3 mx-5" src="{{asset('assets/svg/mockups/mockup.svg')}}" alt="Image description">

                            <figure class="u-shape u-shape--top-right u-shape--position-5">
                                <img src="{{asset('assets/svg/shapes/shape-1.svg')}}" alt="Image description">
                            </figure>
                            <figure class="u-shape u-shape--center-left u-shape--position-6">
                                <img src="{{asset('assets/svg/shapes/shape-2.svg')}}" alt="Image description">
                            </figure>
                            <figure class="u-shape u-shape--center-right u-shape--position-7">
                                <img src="{{asset('assets/svg/shapes/shape-3.svg')}}" alt="Image description">
                            </figure>
                            <figure class="u-shape u-shape--bottom-left u-shape--position-8">
                                <img src="{{asset('assets/svg/shapes/shape-4.svg')}}" alt="Image description">
                            </figure>
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>
</div>

                        
@endsection
