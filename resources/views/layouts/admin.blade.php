 <?php 
    $user = Auth::user(); 
    if ($user->idempresa == 0)
    {
      DB::update('update users set idempresa = '.$user->id.' where email = ?', [$user->email]);
    }
  ?>

<!DOCTYPE html>
<html lang="es" class="no-js">
    <!-- Head -->
    <head>
        <title>Clinicas El Valle</title>

        <!-- Bootstrap 3.3.5 -->
    	<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">

        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="keywords" content="Bootstrap Theme, Freebies, Dashboard, MIT license">
        <meta name="description" content="SZ-Ventas - Recuperar Contraseña">
        <meta name="author" content="szystems.com">
		

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{asset('favico.ico')}}" type="image/x-icon">

        <!-- Web Fonts -->
        <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

        <!-- Components Vendor Styles -->
      <link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">

        <!-- Theme Styles -->
        <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}">

        <!-- Custom Charts -->
        <style>
            .js-doughnut-chart {
                width: 70px !important;
                height: 70px !important;
            }
		</style>
			<!-- datepicker  https://styde.net/formulario-con-datepicker-en-laravel/   https://uxsolutions.github.io/bootstrap-datepicker/?markup=range&format=&weekStart=&startDate=&endDate=&startView=0&minViewMode=0&maxViewMode=4&todayBtn=true&clearBtn=false&language=es&orientation=auto&multidate=&multidateSeparator=&autoclose=on&keyboardNavigation=on&forceParse=on#sandbox-->
				<!-- Jquery -->
				<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
				<!-- Datepicker Files -->
				<link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
				<link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
				<script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
				<!-- Languaje -->
				<script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
			<!-- datepicker end -->
		</head>
		<!-- End Head -->

	<body>
		<!-- Header (Topbar) -->
		<header class="u-header">
            
			<a class="u-header-logo" href="{{ route('login') }}">
                    <img class="u-logo-desktop" src="{{asset('imagenes/logos/logolargo.png')}}" width="160" alt="Clinicas El Valle">
                    <img class="img-fluid u-logo-mobile" src="{{asset('imagenes/logos/logosolo.png')}}" width="50" alt="Clinicas El Valle">
                </a>
			</div>
            

            <div class="u-header-middle">
				<a class="js-sidebar-invoker u-sidebar-invoker" href=""
				   data-is-close-all-except-this="true"
				   data-target="#sidebar">
					<i class="fa fa-bars u-sidebar-invoker__icon--open"></i>
					<i class="fa fa-times u-sidebar-invoker__icon--close"></i>
				</a>

				
			</div>

            <div class="u-header-right">
            
            <!-- User Profile -->
                
                <div class="dropdown ml-2">
                    @if (Auth::guest())      
                        <a class="d-flex align-items-center link-dark" href="{{ route('register') }}">
                            <span class="h2 mb-0"><i class="far fa-list-alt text-muted mr-3"></i></span>Registrarse
                        </a>        
                    @else
                      <a class="link-muted d-flex align-items-center" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                        
						@if (Auth::user()->foto != null)
							<img class="u-avatar--sm rounded-circle mr-3" src="{{asset('imagenes/usuarios/'.Auth::user()->foto)}}">
						@else
							<img class="" src="{{asset('img/user2.png')}}" alt="User Profile">
						@endif
                            <span class="text-dark d-none d-sm-inline-block">
                                {{ Auth::user()->name }} <small class="fa fa-angle-down text-muted ml-1"></small>
                            </span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLink" style="width: 260px;">
                        <div class="card">
                                <div class="card-header py-3">
                                    <!-- Storage -->
                                    <!--<div class="d-flex align-items-center mb-3">
                                        <span class="h6 text-muted text-uppercase mb-0">Licencia: {{ Auth::user()->licencia }}</span>

                                        <div class="ml-auto text-muted">
                                            <span class="h6 text-muted text-uppercase mb-0">Vence: {{ Auth::user()->fecha_vencimiento }}</span>
                                        </div>
                                    </div>

                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>-->
                                    <!-- End Storage -->
                                </div>

                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
										<li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="{{ url('/vistas/vinicio') }}">
                                                <span class="h3 mb-0"><i class="fas fa-store u-sidebar-nav-menu__item-icon"></i></span> Sitio Web
                                            </a>
                                        </li>
										@if(Auth::user()->tipo_usuario != "Doctor")
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="{{URL::action('UsuarioController@show',Auth::user()->id)}}">
                                                <span class="h3 mb-0"><i class="far fa-user-circle text-muted mr-3"></i></span> Perfil
                                            </a>
                                        </li>
										@endif
										@if(Auth::user()->tipo_usuario == "Doctor")
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="{{URL::action('DoctorController@show',Auth::user()->id)}}">
                                                <span class="h3 mb-0"><i class="far fa-user-circle text-muted mr-3"></i></span> Perfil
                                            </a>
                                        </li>
										@endif
										@if(Auth::user()->menu_configuracion == "SI")
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="{{URL::action('ConfiguracionController@edit',Auth::user()->id)}}">
                                                <span class="h3 mb-0"><i class="fas fa-cogs text-muted mr-3"></i></span> Configuración 
                                            </a>
                                        </li>
										@endif
                                        <li>
                                            <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">
                                                <span class="h3 mb-0">
                                                    <i class="far fa-share-square text-muted mr-3">
                                                        
                                                    </i></span> 
                                                    Cerrar Sesion
                                            </a> 
                                        </li>
                                    </ul>
                                </div>
                        </div>
                      </div>
                      @endif
                </div>
                
            <!-- End User Profile -->
            </div>
        </header>
		<!-- End Header (Topbar) -->

		<main class="u-main" role="main">
			<!-- Sidebar -->
			<aside id="sidebar" class="u-sidebar">
				<div class="u-sidebar-inner">
					<header class="u-sidebar-header">
						<a class="u-sidebar-logo" href="">
							<img class="img-fluid" src="{{asset('imagenes/logos/logolargo.png')}}" width="124" alt="SZ-Ventas">
						</a>
					</header>

					<nav class="u-sidebar-nav">
						<ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">
							
							<!-- Panel -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{url('panel')}}" >
									<i class="	fas fa-home u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Panel de Control</b></span>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>
							</li>
							<!-- Web -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{ url('/vistas/vinicio') }}" >
									<i class="	fas fa-store u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Sitio Web</b></span>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>
							</li>

							<!-- Pacientes 
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{url('pacientes\paciente')}}" >
									<i class="fas fa-id-badge u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Pacientes</b></span>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>
							</li>-->

							<!-- Citas -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{url('pacientes\cita')}}" >
									<i class="far fa-calendar-alt u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Citas</b></span>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>
							</li>

							<!-- Pacientes -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="#!"
								   data-target="#subMenu6">
								   <i class="fas fa-id-badge u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Pacientes</b></span>
									<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>

								<ul id="subMenu6" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
									<!-- Components -->
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
							</li>
							<!-- Fin Pacientes -->

							<!-- Farmacia y Rubros -->
							
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="#!"
								   data-target="#subMenu1">
									<i class="fas fa-cubes u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Farmacia y Rubros</b></span>
									<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>

								<ul id="subMenu1" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">

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
							</li>
							
							<!-- Fin Farmacia y Rubros -->

							<!-- Compras -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="#!"
								   data-target="#subMenu2">
									<i class="fas fa-cart-arrow-down u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Compras</b></span>
									<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>

								<ul id="subMenu2" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
									<!-- Components -->
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
							</li>
							<!-- Fin Compras -->
							
							<!-- Ventas -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="#!"
								   data-target="#subMenu3">
									<i class="far fa-handshake u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Ventas</b></span>
									<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>

								<ul id="subMenu3" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
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
							</li>
							<!-- Ventas -->

							<!-- Acceso -->
							
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="#!"
								   data-target="#subMenu4">
									<i class="fas fa-users u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Seguridad</b></span>
									<i class="fa fa-angle-right u-sidebar-nav-menu__item-arrow"></i>
									<span class="u-sidebar-nav-menu__indicator"></span>
								</a>

								<ul id="subMenu4" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">

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
							</li>
							
							<!-- Fin Acceso -->

							<hr>

							<!-- Configuracion -->
							
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{URL::action('ConfiguracionController@edit',Auth::user()->id)}}">
									<i class="fas fa-cogs u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Configuracion</b></span>
								</a>
							</li>
							
							<!-- Fin Configuracion -->

							<!-- Ayuda -->
							<li class="u-sidebar-nav-menu__item">
								<a class="u-sidebar-nav-menu__link" href="{{url('ayuda')}}">
									<i class="far fa-question-circle u-sidebar-nav-menu__item-icon"></i>
									<span class="u-sidebar-nav-menu__item-title"><b>Ayuda</b></span>
								</a>
							</li>
							<!-- Fin Ayuda -->

							
						</ul>
					</nav>
				</div>
			</aside>
			<!-- End Sidebar -->

			<div class="u-content">
				<div class="u-body">
					
						<!-- Doughnut Chart -->
						@yield('contenido')
						<!-- End Current Projects -->
				</div>

				<!-- Footer -->
				<footer class="u-footer d-md-flex align-items-md-center text-center text-md-left text-muted text-muted">
					<p class="h5 mb-2 mb-md-0">Version 1.0 <a class="link-muted" href="https://szystems.com/" target="_blank">Szystems</a></p>

					<p class="h5 mb-0 ml-auto">
						&copy; <script>document.write(new Date().getFullYear());</script> <a class="link-muted" href="https://szystems.com/" target="_blank">Szystems</a>. Todos los derechos reservados.
					</p>
				</footer>
				<!-- End Footer -->
			</div>
		</main>

		<!-- Global Vendor -->
		<script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    	@stack('scripts')
		<script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('assets/vendor/jquery-migrate/jquery-migrate.min.js')}}"></script>
        <script src="{{asset('assets/vendor/popper.js/dist/umd/popper.min.js')}}"></script>
        <script src="{{asset('assets/vendor/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
        <!-- Plugins -->
        <script src="{{asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
        <script src="{{asset('assets/vendor/chart.js/dist/Chart.min.js')}}"></script>

        <!-- Initialization  -->
        <script src="{{asset('assets/js/sidebar-nav.js')}}"></script>
        <script src="{{asset('assets/js/main.js')}}"></script>
        <script src="{{asset('assets/js/dashboard-page-scripts.js')}}"></script>


     

   

	</body>
</html>