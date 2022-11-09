<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="description" content="Singular BPO">
<meta name="author" content="Szystems.com">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<title>Singular BPO</title>
<link rel="icon" href="{{asset('img/logos/favico.ico')}}">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<!-- /Google Fonts -->

<!-- Styles -->
<link type="text/css" rel="stylesheet" href="{{asset('singulartemplate/html/css/base.css?ver=3')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('singulartemplate/html/css/owl-carousel.css?ver=3')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('singulartemplate/html/css/style.css?ver=3')}}" />
<!--[if lt IE 9]> <script src="{{asset('singulartemplate/html/js/modernizr.custom.js?ver=3')}}"></script> <![endif]-->
<!-- /Styles -->

</head>

<body>


<!-- Wrapper All -->
<div class="resumo_fn_wrapper">

	<!-- MODALBOX -->
	<div class="resumo_fn_modalbox">
		<a class="extra_closer" href="#"></a>
		<div class="box_inner">
			<a class="closer" href="#"><span></span></a>
			<div class="modal_content">
				
				<div class="modal_in">
					<!-- Content comes from JS -->
				</div>
				
				<div class="fn__nav" data-from="" data-index="">
					<a href="#" class="prev">
						<span class="text">Prev</span>
						<span class="arrow_wrapper"><span class="arrow"></span></span>
					</a>
					<a href="#" class="next">
						<span class="text">Next</span>
						<span class="arrow_wrapper"><span class="arrow"></span></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /MODALBOX --> 
	
	<div class="resumo_fn_content">
		
		<!-- Main Left Part -->
		<div class="resumo_fn_left">

			<!-- Page -->
			<div class="resumo_fn_page">


			@yield('content')


			</div>
			<!-- /Page -->


			<footer id="footer">
				<div class="footer_top">
					<a href="#" class="resumo_fn_totop"><span></span></a>
				</div>
				<div class="footer_content">
					<div class="container">
						<p>Copyright © <script>document.write(new Date().getFullYear());</script>. Todos los derechos reservados. <br />
						Diseñado &amp; Desarrollado por <a class="fn__link" href="https://singular.com.gt/" target="_blank">Singular BPO</a> &amp; <a class="fn__link" href="https://szystems.com/" target="_blank">Szystems</a></p>
					</div>
				</div>
			</footer>


		</div>
		<!-- /Main Left Part -->

		<!-- Main Right Part -->
		<div class="resumo_fn_right">

			<!-- Menu Triggger -->
			<a href="#" class="menu_trigger">
				<span class="text">Menu</span>
				<span class="hamb">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</a>
			<!-- /Menu Triggger -->

			<!-- Panel Content -->
			<div class="right_in">
				<div class="right_top">
					<div class="border1"></div><div class="border2"></div>

					<div class="img_holder">
						<img src="{{asset('img/logos/logo.png')}}" alt="">
						<div class="abs_img" data-bg-img="{{asset('img/logos/logo.png')}}"></div>
					</div>
					<div class="title_holder">
						<h5>Nosotros somos:</h5>
						<h3>
							<span class="animated_title">
								<span class="title_in">Logística Corporativa</span>
								<span class="title_in">Mercadeo</span>
								<span class="title_in">Gestión del talento humano</span>
								<span class="title_in">SINGULAR</span>
							</span>
						</h3>
					</div>
				</div>
				<div class="right_bottom">
					<a href="#portfolio">
						<span class="circle"></span>
						<span class="text">+40 Marcas y organizaciones
                            confian en nuestro trabajo</span>
					</a>
				</div>
			</div>
			<!-- /Panel Content -->

		</div>
		<!-- /Main Right Part -->
	
	</div>
	
	<!-- Right Hidden Navigation -->
	<a href="#" class="resumo_fn_nav_overlay"></a>
	<div class="resumo_fn_navigation">
		<a href="#" class="closer"></a>
		
		<!-- Navigation Content -->
		<div class="nav_in">
			
			<nav id="nav">
				<h3 class="label">Menu</h3>
				<ul>
					<li><a href="#home">Inicio</a></li>
					<li><a href="#about">Sobre Nosotros</a></li>
                    <li><a href="#servicios">¿Qué hacemos?</a></li>
					<li><a href="#portfolio">Proyectos</a></li>
					<li><a href="#equipo">Equipo</a></li>
					{{-- <li><a href="#customers">Customers</a></li>
					<li><a href="#news">News &amp; Tips</a></li> --}}
					<li><a href="#contact">Contacto</a></li>
				</ul>
			</nav>
			
			<div class="nav_footer">
				<div class="social">
					<ul>
						<li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/svg/social/twitter.svg')}}" alt="" class="fn__svg"></a></li>
						<li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/svg/social/facebook.svg')}}" alt="" class="fn__svg"></a></li>
						<li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/svg/social/instagram.svg')}}" alt="" class="fn__svg"></a></li>
						<li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/svg/social/pinterest.svg')}}" alt="" class="fn__svg"></a></li>
						<li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/svg/social/behance.svg')}}" alt="" class="fn__svg"></a></li>
					</ul>
				</div>
				<div class="copyright">
					Diseñado &amp; Desarrollado por <a class="fn__link" href="https://singular.com.gt/" target="_blank">Singular BPO</a> &amp; <a class="fn__link" href="https://szystems.com/" target="_blank">Szystems</a>
				</div>
			</div>
			
		</div>
		<!-- /Navigation Content -->
		
	</div>
	<!-- /Right Hidden Navigation -->
	
	
	<div class="frenify-cursor cursor-outer" data-default="yes" data-link="yes" data-slider="yes"><span class="fn-cursor"></span></div>
	<div class="frenify-cursor cursor-inner" data-default="yes" data-link="yes" data-slider="yes"><span class="fn-cursor"><span class="fn-left"></span><span class="fn-right"></span></span></div>
	
</div>
<!-- /Wrapper All -->



<!-- Scripts -->
<script src="{{asset('singulartemplate/html/js/jquery.js?ver=3')}}"></script>
<script src="{{asset('singulartemplate/html/js/typed.js?ver=3')}}"></script>
<script src="{{asset('singulartemplate/html/js/owl-carousel.js?ver=3')}}"></script>
<script src="{{asset('singulartemplate/html/js/waypoints.js?ver=3')}}"></script>
<script src="{{asset('singulartemplate/html/js/nicescroll.js?ver=3')}}"></script>
<!--[if lt IE 10]> <script src="{{asset('singulartemplate/html/js/ie8.js?ver=3')}}"></script> <![endif]-->
<script src="{{asset('singulartemplate/html/js/init.js?ver=3')}}"></script>
<!-- /Scripts -->

</body>
</html>