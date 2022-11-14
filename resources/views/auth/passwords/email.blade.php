@extends('layouts.app')

@section('content')
<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="roww resumo_fn_contact">

            <!-- Main Title -->
            <div class="resumo_fn_main_title">
                <h3 class="subtitle">Olvidaste tu contraseña?</h3>
                <h3 class="title">Recupera tu cuenta</h3>
                <p class="desc">Escribe tu correo de usuario y se te enviara un correo para poder recuperar tu cuenta.</p>
            </div>
            <!-- /Main Title -->

            <!-- Contact Form -->
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

            <form class="contact_form"  method="POST" action="{{ route('password.email') }}">
                
                {{ csrf_field() }}

                <div class="items_wrap">
                    <div class="items">
                        <div class="item">
                            <label for="email">Tu email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="item ">
                            <button class="button button3" type="submit"><i class="fas fa-check-double"></i> Enviar Correo</button>
                        </div>
                    </div>
                </div>
            </form>
            <button class="button button2" onclick="window.location.href='{{ route('login') }}';">Ya recordaste tu contraseña?</button>
            <!-- /Contact Form -->

        </div>
    </div>
</section>
<!-- /Contact Section -->
@endsection
