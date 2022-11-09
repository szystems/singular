@extends ('layouts.admin')
@section ('contenido')
<?php 
    $user = Auth::user(); 
?>
<div>
      <div class="card mb-4">
            <header class="card-header" align="center">
				<h2 class="h3 card-header-title"><strong>Panel de Control de: </h2>
				@if(Auth::user()->logo == null)
					<h1><u><strong><font color="orange">{{Auth::user()->empresa}}</font></strong></u></strong></h1>
				@else
					<img src="{{asset('imagenes/logos/'.Auth::user()->logo)}}"  width="150">
				@endif	
				<h5 class="h5 card-header-title"><strong>Usuario: <font color="blue">{{Auth::user()->name}}</font></strong></h5>
            </header>
            <div class="card-body" >
				<h2><u><b>Modulos</b></u></h2>
				<div id="accordion" >
					
						<div class="card">
							<div class="card">
							<div class="card-header">
								<a href="{{ url('/vistas/vinicio') }}">
									<i class="fas fa-store u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
									<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Sitio Web </b></font></span>
								</a>
							</div>
							<!--<div class="card-header">
								<a href="{{url('pacientes\paciente')}}">
									<i class="fas fa-id-badge u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
									<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Pacientes</b></font></span>
								</a>
							</div>-->
							<div class="card-header">
								<a href="{{url('pacientes\cita')}}">
									<i class="far fa-calendar-alt u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
									<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Citas</b></font></span>
								</a>
							</div>
							
						</div>
						
						
					</div>
					
					
					
					
					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapse6">
								<i class="fas fa-id-badge u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Pacientes </b></font></span>
								<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
								<span class="u-sidebar-nav-menu__indicator"></span>
							</a>
						</div>
						<div id="collapse6" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<ul >
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('pacientes\historiales')}}">
											<span class="u-sidebar-nav-menu__item-icon">H</span>
											<span class="u-sidebar-nav-menu__item-title">Historiales</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('pacientes\paciente')}}">
											<span class="u-sidebar-nav-menu__item-icon">P</span>
											<span class="u-sidebar-nav-menu__item-title">Pacientes</span>
										</a>
									</li>
									
								</ul>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							<a class="card-link" data-toggle="collapse" href="#collapse1">
								<i class="fas fa-cubes u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Farmacia y Rubros </b></font></span>
								<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
								<span class="u-sidebar-nav-menu__indicator"></span>
							</a>
						</div>
						<div id="collapse1" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<ul>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('almacen\articulo')}}">
											<span class="u-sidebar-nav-menu__item-icon">A</span>
											<span class="u-sidebar-nav-menu__item-title">Articulos</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('almacen\categoria')}}">
											<span class="u-sidebar-nav-menu__item-icon">C</span>
											<span class="u-sidebar-nav-menu__item-title">Categorías</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('almacen\presentacion')}}">
											<span class="u-sidebar-nav-menu__item-icon">P</span>
											<span class="u-sidebar-nav-menu__item-title">Presentaciones</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('ventas\rubro')}}">
											<span class="u-sidebar-nav-menu__item-icon">R</span>
											<span class="u-sidebar-nav-menu__item-title">Rubros</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapse2">
								<i class="fas fa-cart-arrow-down u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Compras </b></font></span>
								<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
								<span class="u-sidebar-nav-menu__indicator"></span>
							</a>
						</div>
						<div id="collapse2" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<ul >
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('compras\ingreso')}}">
											<span class="u-sidebar-nav-menu__item-icon">I</span>
											<span class="u-sidebar-nav-menu__item-title">Ingresos</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('compras\proveedor')}}">
											<span class="u-sidebar-nav-menu__item-icon">P</span>
											<span class="u-sidebar-nav-menu__item-title">Proveedores</span>
										</a>
									</li>
									
								</ul>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapse3">
								<i class="far fa-handshake u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Ventas </b></font></span>
								<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
								<span class="u-sidebar-nav-menu__indicator"></span>
							</a>
						</div>
						<div id="collapse3" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<ul >
								<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('ventas\inventario')}}">
											<span class="u-sidebar-nav-menu__item-icon">I</span>
											<span class="u-sidebar-nav-menu__item-title">Inventario</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('ventas\orden')}}">
											<span class="u-sidebar-nav-menu__item-icon">O</span>
											<span class="u-sidebar-nav-menu__item-title">Ordenes</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('ventas\venta')}}">
											<span class="u-sidebar-nav-menu__item-icon">V</span>
											<span class="u-sidebar-nav-menu__item-title">Ventas</span>
										</a>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
					
					
					<div class="card">
						<div class="card-header">
							<a class="card-link" data-toggle="collapse" href="#collapse4">
								<i class="fas fa-users u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Seguridad </b></font></span>
								<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
								<span class="u-sidebar-nav-menu__indicator"></span>
							</a>
						</div>
						<div id="collapse4" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<ul>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('reportes\bitacora')}}">
											<span class="u-sidebar-nav-menu__item-icon">B</span>
											<span class="u-sidebar-nav-menu__item-title">Bitácora</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('seguridad\doctor')}}">
											<span class="u-sidebar-nav-menu__item-icon">D</span>
											<span class="u-sidebar-nav-menu__item-title">Doctores</span>
										</a>
									</li>
									<li class="u-sidebar-nav-menu__item">
										<a class="u-sidebar-nav-menu__link" href="{{url('seguridad\usuario')}}">
											<span class="u-sidebar-nav-menu__item-icon">U</span>
											<span class="u-sidebar-nav-menu__item-title">Usuarios</span>
										</a>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
					
					<div class="card">
						<div class="card-header">
							<a href="{{URL::action('ConfiguracionController@edit',Auth::user()->id)}}">
								<i class="fas fa-cogs u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Configuracion </b></font></span>
							</a>
						</div>
						
					</div>
					
					<div class="card">
						<div class="card-header">
							<a href="{{url('ayuda')}}">
								<i class="far fa-question-circle u-sidebar-nav-menu__item-icon" style="font-size:30px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>
								<span class="u-sidebar-nav-menu__item-title"><font color="orange"><b>Ayuda </b></font></span>
							</a>
						</div>
						
					</div>
				</div>
			</div>
            <footer class="card-footer">
                 
            </footer>
      </div>
</div>
@endsection