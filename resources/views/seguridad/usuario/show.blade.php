@extends ('layouts.admin')
@section ('contenido')


<div>
      <div class="card mb-4">
            <header class="card-header">
                  <h2 class="h3 card-header-title"><strong>Usuario: {{$usuario->name}}</strong></h2>
                  
            </header>
            {{Form::open(array('action' => 'ReportesController@vistausuario','method' => 'POST','role' => 'form', 'target' => '_blank'))}}

                {{Form::token()}}		
					<div class="card mb-4">
						<header class="card-header d-md-flex align-items-center">
							<h4><strong>Imprimir </strong></h4>
							<input type="hidden" id="rid" class="form-control datepicker" name="rid" value="{{$usuario->id}}">
                            <input type="hidden" id="rnombre" class="form-control datepicker" name="rnombre" value="{{$usuario->name}}">
						</header>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
									<div class="form-group mb-2">
										<select name="pdf" class="form-control" value="">
												<option value="Descargar" selected>Descargar</option>
												<option value="Navegador">Ver en navegador</option>
											</select>
									</div>
								</div>
								<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
									<div class="form-group mb-2">
										<span class="input-group-btn">
											<button type="submit" class="btn btn-danger">
												<i class="fa fa-file-pdf"></i> PDF
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				{{Form::close()}}
            <div class="card-body">
                @if(Auth::user()->tipo_usuario == "Administrador")
                    <a href="{{URL::action('UsuarioController@edit',$usuario->id)}}">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Editar Usuario">
                            <button class="btn btn-sm btn-info" style="pointer-events: none;" type="button"><i class="far fa-edit"></i> Editar</button>
                        </span>
                    </a>		
                    <a href="" data-target="#modaleliminarshow-delete-{{$usuario->id}}" data-toggle="modal">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Eliminar Usuario">
                            <button class="btn btn-sm btn-danger" style="pointer-events: none;" type="button"><i class="far fa-minus-square"></i> Eliminar</button>
                        </span>
                    </a>
                @endif
                  <div class="row">
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="name"><strong>Nombre</strong></label>
                            <p>{{$usuario->name}}</p>
                        </div>
                    </div>
                    <?php
                        $fecha_nacimiento = date("d-m-Y", strtotime($usuario->fecha_nacimiento));

                        $cumpleanos = new DateTime($usuario->fecha_nacimiento);
                        $hoy = new DateTime();
                        $annos = $hoy->diff($cumpleanos);
                        $edad = $annos->y;
                    ?>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="fecha_nacimiento"><strong>Fecha Nacimiento</strong></label>
                            <p>{{$fecha_nacimiento}} (Edad: {{$edad}})</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="email"><strong>Email</strong></label>
                            <p>{{$usuario->email}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="empresa"><strong>Empresa</strong></label>
                            <p>{{$usuario->empresa}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="telefono"><strong>Teléfono</strong></label>
                            <p>{{$usuario->telefono}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="direccion"><strong>Dirección</strong></label>
                            <p>{{$usuario->direccion}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="contacto_emergencia"><strong>Contacto de Emergencia</strong></label>
                            <p>{{$usuario->contacto_emergencia}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="telefono_emergencia"><strong>Teléfono de Emergencia</strong></label>
                            <p>{{$usuario->telefono_emergencia}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="max_descuento"><strong>Porcentaje Máximo de Descuento</strong></label>
                            <p>{{$usuario->max_descuento}}<i class="fas fa-percent"></i></p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label for="tipo_usuario"><strong>Tipo Usuario</strong></label>
                            <p>{{$usuario->tipo_usuario}}</p>
                        </div>
                    </div>
                  </div>
            </div>
			@include('seguridad.usuario.modaleliminarshow')
                
                        
              

            <footer class="card-footer">
                  

        
            </footer>
      </div>
</div>
   
@endsection