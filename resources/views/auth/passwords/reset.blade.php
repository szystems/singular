@extends('layouts.app')

@section('content')
<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="roww resumo_fn_contact">

            <!-- Main Title -->
            <div class="resumo_fn_main_title">
                <h3 class="subtitle">Olvidaste tu contraseña?</h3>
                <h3 class="title">Resetea tu contraseña</h3>
                <p class="desc">Escribe la contraseña nueva dos veces, si tu no pediste resetear tu contraseña has caso omiso y cierra esta página.</p>
            </div>
            <!-- /Main Title -->

            <!-- Contact Form -->
            <form class="contact_form"  method="POST" action="{{ url('/password/reset') }}">
                
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="items_wrap">
                    <div class="items">
                        <div class="item">
                            <label for="email">Tu email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="item half">
                            <label for="password">Nueva Contraseña</label>
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="item half">
                            <label for="password-confirm">Confirma Contraseña</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="item ">
                            <button class="button button3" type="submit"><i class="fas fa-check-double"></i> Resetear Contraseña</button>
                        </div>
                    </div>
                </div>
            </form>
            <button class="button button2" onclick="window.location.href='{{ route('login') }}';"></button>
            <!-- /Contact Form -->

        </div>
    </div>
</section>
<!-- /Contact Section -->
@endsection
