@extends ('layouts.admin')
@section ('contenido')
<?php 
    $user = Auth::user(); 
?>
<div>
      <div class="card mb-4">
            <header class="card-header" align="center">
				<h1 class="h1 card-header-title"><strong><i class="far fa-question-circle u-sidebar-nav-menu__item-icon"></i> Ayuda </h1>
            </header>
            <div class="card-body" >
				<h2>Hola <b>{{Auth::user()->name}}</b>, ¿necesitas ayuda?</h2>
                <p>Puedes comunicarte con el administrador inmediato de la aplicación o con nosotros en <a href="https://szystems.com/" target="_blank">Szystems</a> , para nosotros es un gusto poder ayudarte.</p>
				<p>¿Dónde te puedes comunicar con <a href="https://szystems.com/" target="_blank">Szystems</a>?</p>
                <div class="list-group">
                    <a href="https://www.facebook.com/szystems" target="_blank" class="list-group-item list-group-item-action"><i class="fab fa-facebook u-sidebar-nav-menu__item-icon"></i>Facebook</a> 
                    <a href="http://wpp-redirect.herokuapp.com/go/?p=50242153288&m=" target="_blank" class="list-group-item list-group-item-action"><i class="fab fa-whatsapp u-sidebar-nav-menu__item-icon"></i>Whatsapp</a>
                    <a href="tel:+50232056298" target="_blank" class="list-group-item list-group-item-action"><i class="	fas fa-mobile-alt u-sidebar-nav-menu__item-icon"></i>Telefono Movil (502 4215-3288)</a>
                  </div>
                <a href="https://szystems.com/"><img src="img/Szystems.png" alt="Szystems" class="img-fluid img-thumbnail" target="_blank"></a>
			</div>
            <footer class="card-footer">
                 
            </footer>
      </div>
</div>
@endsection