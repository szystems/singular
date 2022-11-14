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