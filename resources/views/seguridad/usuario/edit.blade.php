@extends ('layouts.admin')
@section ('contenido')


<div class="col-md-6 mb-4">
      <div class="card">
            <header class="card-header">
                  <h2 class="h3 card-header-title"><strong>Editar Administrador: {{ $usuario->name}} </strong></h2>
            </header>
            
            <!-- .flash-message -->
            <div class="flash-message">
                  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                              <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        @endif
                   @endforeach
            </div> 
            <!-- fin .flash-message -->

            <div class="card-body">
                  <h5 class="h4 card-title">Cambie los datos que desee editar:</h5>
                  <h6><font color="orange"> Campos Obligatorios *</font></h6>
                  @if (count($errors)>0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                        </ul>
                    </div>
                  @endif

                  {!!Form::model($usuario,['method'=>'PATCH','route'=>['usuario.update',$usuario->id],'files'=>'true'])!!}
                  {{Form::token()}}
                  <h3><strong><u>Datos Generales: </u></strong></h3>
                  <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name"><font color="orange">*</font>Nombre</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{$usuario->name}}" >
                        @if ($errors->has('name'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('name') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">Email </label>
                        <input id="email" type="text" class="form-control" name="email" value="{{$usuario->email}}">
                        @if ($errors->has('email'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('email') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <?php
                        $fecha_nacimiento = date("d-m-Y", strtotime($usuario->fecha_nacimiento));
                  ?>
                  <div class="form-group{{ $errors->has('fecha_nacimiento') ? ' has-error' : '' }}">
                        <label for="fecha_nacimiento"><font color="orange">*</font>Fecha Nacimiento</label>
                        <span class="form-icon-wrapper">
                              <span class="form-icon form-icon--right">
                                    <i class="fas fa-calendar-alt form-icon__item"></i>
                              </span>
                              <input type="text" id="fechanacimiento" class="form-control datepicker" name="fecha_nacimiento" value="{{$fecha_nacimiento}}">
                        </span>
                  </div>
                  <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" type="text" class="form-control" name="telefono" value="{{$usuario->telefono}}">
                        @if ($errors->has('telefono'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('telefono') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
                        <label for="direccion">Dirección</label>
                        <input id="direccion" type="text" class="form-control" name="direccion" value="{{$usuario->direccion}}">
                        @if ($errors->has('direccion'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('direccion') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <div class="form-group{{ $errors->has('contacto_emergencia') ? ' has-error' : '' }}">
                        <label for="direccion">Contacto de Emergencia</label>
                        <input id="contacto_emergencia" type="text" class="form-control" name="contacto_emergencia" value="{{$usuario->contacto_emergencia}}" placeholder="Contacto de Emergencia">
                        @if ($errors->has('contacto_emergencia'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('contacto_emergencia') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <div class="form-group{{ $errors->has('telefono_emergencia') ? ' has-error' : '' }}">
                        <label for="telefono">Teléfono de Emergencia</label>
                        <input id="telefono_emergencia" type="text" class="form-control" name="telefono_emergencia" value="{{$usuario->telefono_emergencia}}" placeholder="Teléfono del contacto de emergencia">
                        @if ($errors->has('telefono_emergencia'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('telefono_emergencia') }}
                                    </strong>
                              </span>
                        @endif
                  </div>
                  <div class="form-group">
                        <label for="foto">Imagen</label>
                        <input type="file" name="foto">
                        @if (($usuario->foto)!="")
                               <img src="{{asset('imagenes/usuarios/'.$usuario->foto)}}" height="300px" >
                        @endif
                  </div>
                  <h3><strong><u>Accesos: </u></strong></h3>
                  <div class="form-group{{ $errors->has('tipo_usuario') ? ' has-error' : '' }}">
                        <label for="name"><font color="orange">*</font>Tipo de Usuario</label>
                        <select id="tipo_usuario" type="text" class="form-control" name="tipo_usuario" value="{{$usuario->tipo_usuario}}">
                            <option selected="selected" value="{{$usuario->tipo_usuario}}">{{$usuario->tipo_usuario}}</option> 
                            <option value="Administrador">Administrador</option>
                            <option value="Trabajador">Trabajador</option>   
                        </select>
                        @if ($errors->has('tipo_usuario'))
                              <span class="help-block">
                                    <strong>
                                          {{ $errors->first('tipo_usuario') }}
                                    </strong>
                              </span>
                        @endif
                  </div>  

            </div>

            <footer class="card-footer">
                  <div class="form-group">
                        <!--enviamos idcliente para editar los datos de cliente-->

                        <button class="btn btn-danger" type="reset"><i class="fas fa-ban"></i> Borrar</button>
                        <button class="btn btn-info" type="submit"><i class="far fa-save"></i> Guardar</button>
                  </div>

                  {!!Form::close()!!}
            </footer>
      </div>
</div>
<script>
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        var optSimple = {
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true,
            todayHighlight: true,
            todayBtn: "linked",
        };
        $( '#fechanacimiento' ).datepicker( optSimple );
    </script>
@push ('scripts')
    <script>
        

        function validardecimal(e,txt) 
        {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla==8) return true;
            if (tecla==46 && txt.indexOf('.') != -1) return false;
            patron = /[\d\.]/;
            te = String.fromCharCode(tecla);
            return patron.test(te); 
        }  

        function validarentero(e,txt) 
        {
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8)
            {
                return true;
            }
        
        // Patron de entrada, en este caso solo acepta numeros
        patron =/[0-9]/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final); 
        }
    </script>
@endpush
@endsection