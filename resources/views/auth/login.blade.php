@extends('layouts.app')

@section('content')
<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="roww resumo_fn_contact">

            <!-- Main Title -->
            <div class="resumo_fn_main_title">
                <h3 class="subtitle">Login</h3>
                <h3 class="title">Entra a tu cuenta</h3>
                <p class="desc">Escribe tu email de usuario y contraseña</p>
            </div>
            <!-- /Main Title -->

            <!-- Contact Form -->
            <form class="contact_form"  method="POST" action="{{ route('login') }}">
                
                {{ csrf_field() }}

                <div class="items_wrap">
                    <div class="items">
                        <div class="item half">
                            <div class="form-group mb-6{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">Tu email</label>
                                <input id="email" class="form-control" name="email" type="email" placeholder="juan@example.com" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="item half">
                            <div class="form-group mb-6{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" name="password" type="password" placeholder="tu password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="item ">
                            <button class="button button3" type="submit"><i class="fa fa-check"></i> Entrar</button>
                            
                        </div>
                    </div>
                </div>
            </form>
            <button class="button button2" onclick="window.location.href='{{ route('password.request') }}';">Olvidaste tu contraseña?</button>
            
            <!-- /Contact Form -->

        </div>
    </div>
</section>
<!-- /Contact Section -->
    
@endsection
