@extends('layouts.app')
@section('content')
    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div align="center" class="resumo_fn_main_title">
                    <img src="{{asset('img/logos/logo1.png')}}" alt="" height="200">
                    {{-- <h3 class="subtitle">Introducción</h3> --}}
                    {{-- <h3 class="title">Introducción</h3> --}}
                    <p class="desc">Singular BPO es una empresa que nació en Quetzaltenango en 2019, que se enfoca en potencializar las capacidades profesionales de organizaciones y marcas a través de la planificación de eventos corporativos, mercadeo, comunicación y gestión de talento humano.</p>
                    
                </div>
                <!-- /Main Title -->

            </div>
        </div>
    </section>
    <!-- /Home Section -->


    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    {{-- <h3 class="subtitle">About Me</h3> --}}
                    <h3 class="title">Sobre Nosotros</h3>
                    <p class="desc">Singular BPO a lo largo de estos años ha trabajado planes de formación y generación de nuevas capacidades C4D, mercadeo, consultorías en negocios, herramientas digitales, redes sociales, diagramación, diseño gráfico; y logística corporativa de recursos humanos finanzas y marketing para más de 25 empresas y organizaciones en Quetzaltenango, cuyo trabajo ha abarcado centroamérica, algunos países del caribe y europa como República Dominicana, Suecia, España y Bélgica.

                        Nuestro equipo está integrado por profesionales en comunicación, mercadeo, administración, diseño gráfico, producción audiovisual, relaciones públicas, talento humano, formación y desarrollo.</p>
                </div>
                <!-- /Main Title -->

                <!-- About Information -->
                {{-- <div class="resumo_fn_about_info">
                    <div class="about_left">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Bruce Wilson</th>
                            </tr>
                            <tr>
                                <th>Birthday</th>
                                <th>4th April 1990</th>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <th>31 years</th>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <th>San Francisco</th>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <th><a href="tel:+3846923442364">(+38) 469 2344 2364</a></th>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <th><a href="mailto:frenifyteam@gmail.com">frenifyteam@gmail.com</a>
                                </th>
                            </tr>
                            <tr>
                                <th>Skype</th>
                                <th><a href="skype:brucewilson.90">brucewilson.90</a></th>
                            </tr>
                        </table>
                    </div>
                    <div class="about_right">
                        <!-- Download CV Button -->
                        <div class="resumo_fn_cv_btn">
                            <a href="{{asset('singulartemplate/html/img/cv.jpg')}}" download>
                                <span class="icon">
                                    <img src="{{asset('singulartemplate/html/svg/inbox.svg')}}" alt="" class="fn__svg" />
                                    <img src="{{asset('singulartemplate/html/svg/arrow.svg')}}" alt="" class="fn__svg arrow" />
                                </span>
                                <span>Download CV</span>
                            </a>
                        </div>
                        <!-- /Download CV Button -->
                    </div> 
                </div> --}}
                <!-- /About Information -->


                <!-- Tabs Shortcode -->
                <div class="resumo_fn_tabs" id="servicios">
                    <h3 class="title">¿Qué hacemos?</h3>
                    

                    <!-- Tab: Header -->
                    <div class="tab_header">
                        <ul>
                            <li class="active"><a href="#tab1">Servicios</a></li>
                            {{-- <li><a href="#tab2">Education</a></li>
                            <li><a href="#tab3">Skills</a></li> --}}
                        </ul>
                    </div>
                    <!-- /Tab: Header -->

                    <!-- Tab: Content -->
                    <div class="tab_content">

                        <!-- #1 tab content -->
                        <div id="tab1" class="tab_item active">

                            <!-- Boxed List -->
                            <div class="resumo_fn_boxed_list">
                                <ul>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                {{-- <h5>Frenify LLC</h5>
                                                <span>( 2018 — Today )</span> --}}
                                            </div>
                                            <h3>Logística Corporativa</h3>
                                            <p>Gestión Integral de eventos corporativos; creación, planificación, organización, producción y ejecución utilizando las herramientas idóneas a las necesidades y presupuesto de cada marca y organización.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                {{-- <h5>Google LLC</h5>
                                                <span>( 2016 — 2018 )</span> --}}
                                            </div>
                                            <h3>Mercadeo</h3>
                                            <p>Potencializamos las capacidades comerciales de nuestros clientes a través de la adecuada planificación y comunicación de mercadeo.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                {{-- <h5>Twitter LLC</h5>
                                                <span>( 2016 — 2011 )</span> --}}
                                            </div>
                                            <h3>Gestión del Talento Humano</h3>
                                            <p>Entendemos el rol de humanizar cada vez más las organizaciones a través de la formación, fortalecimiento de capacidades y aptitudes en busca de la profesionalización.Enfocados en crear lugares de trabajo adecuados para los colaboradores de cada organización a la que asesoramos.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Boxed List -->

                        </div>
                        <!-- /#1 tab content -->

                        <!-- #2 tab content -->
                        {{-- <div id="tab2" class="tab_item">

                            <!-- Boxed List -->
                            <div class="resumo_fn_boxed_list">
                                <ul>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                <h5>Frenify University</h5>
                                                <span>( 2014 — 2017 )</span>
                                            </div>
                                            <h3>Computer Science</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                <h5>Edu University</h5>
                                                <span>( 2011 — 2014 )</span>
                                            </div>
                                            <h3>Master Degree</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                <h5>Clolumbia College</h5>
                                                <span>( 2007 — 2011 )</span>
                                            </div>
                                            <h3>Bachelor Degree</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Boxed List -->

                        </div>
                        <!-- /#2 tab content -->

                        <!-- #3 tab content -->
                        <div id="tab3" class="tab_item">

                            <!-- Progress Bar -->
                            <div class="resumo_fn_progress_bar">

                                <div class="progress_item" data-value="92">
                                    <div class="item_in">
                                        <h3 class="progress_title">Adobe Photoshop</h3>
                                        <span class="progress_percent"></span>
                                        <div class="bg_wrap">
                                            <div class="progress_bg"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress_item" data-value="95">
                                    <div class="item_in">
                                        <h3 class="progress_title">HTML5 &amp; CSS3</h3>
                                        <span class="progress_percent"></span>
                                        <div class="bg_wrap">
                                            <div class="progress_bg"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress_item" data-value="80">
                                    <div class="item_in">
                                        <h3 class="progress_title">WordPress</h3>
                                        <span class="progress_percent"></span>
                                        <div class="bg_wrap">
                                            <div class="progress_bg"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress_item" data-value="85">
                                    <div class="item_in">
                                        <h3 class="progress_title">Adobe Illustrator</h3>
                                        <span class="progress_percent"></span>
                                        <div class="bg_wrap">
                                            <div class="progress_bg"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /Progress Bar -->

                            <!-- Description -->
                            <div class="resumo_fn_desc">
                                <p>A freelance creative designer with a love for minimal design, clean
                                    typography and well-written code, located in San Francisco. Provide
                                    high quality and cost effective offshore web and software
                                    development services. Wide range of web and software development
                                    services across the world.</p>
                            </div>

                        </div> --}}
                        <!-- /#2 tab content -->

                    </div>
                    <!-- /Tab: Content -->
                </div> 
                <!-- /Tabs Shortcode -->


            </div>
        </div>
    </section>
    <!-- /About Section -->



    <!-- Portfolio Section -->
    <section id="portfolio">

        <div class="container">
            <div class="roww">
                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <div class="my__nav">
                        <a href="#" class="prev"><span></span></a>
                        <a href="#" class="next"><span></span></a>
                    </div>
                    {{-- <h3 class="subtitle">Proyectos</h3> --}}
                    <h3 class="title">+40 Organizaciones y marcas
                        confian en nuestro trabajo</h3>
                </div>
                <!-- /Main Title -->
            </div>
        </div>

        <div class="container noright">
            <div class="roww">

                <div class="owl-carousel modal_items" data-from="portfolio" data-count="5">
                    <div class="item" data-index="1">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/AA.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/AA.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Abarroterías Albamar</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="2">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/ach.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/ach.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Acción Contra el Hambre</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="3">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/asedechi.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/asedechi.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>ASEDECHI</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="4">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/COMFORT.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/COMFORT.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Comfort Dreams</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="5">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/CRS.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/CRS.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Catholic Relief Services</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="6">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/dinamica.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/dinamica.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Dinamica</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="7">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/doc.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/doc.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Pan Don Chabelo</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="8">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/eco.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/eco.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>>Asociación ECO</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="9">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/floresta.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/floresta.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Parque Floresta</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="10">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/grupoCR.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/grupoCR.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>CR Grupo</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="11">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/HGA.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/HGA.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Helvetas</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="12">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/Mayalatex.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/Mayalatex.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Mayalatex</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="13">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/MM.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/MM.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Mas y Mas</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="14">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/muni.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/muni.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Municipalidad de Quetzaltenango</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="15">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/OXFAM.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/OXFAM.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>OXFAM</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="16">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/ppd.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/ppd.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>PPD DR-CAFTA</h3>
                        </div>
                        
                    </div>
                    <div class="item " data-index="17">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/ROTARY.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/ROTARY.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Club Rotario</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="18">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/SAN.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/SAN.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>SAN</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="19">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/sinai.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/sinai.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Colegio Sinai</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="20">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/Special.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/Special.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Special Home</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="21">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/TGT.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/TGT.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>Traeguate</h3>
                        </div>
                        
                    </div>
                    <div class="item" data-index="22">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/WE.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/WE.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3>We Effect</h3>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
        

        <div class="container">
            <div class="roww">
                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <div class="my__nav">
                        <a href="#" class="prev"><span></span></a>
                        <a href="#" class="next"><span></span></a>
                    </div>
                    <br><br><br>
                    {{-- <h3 class="subtitle">Proyectos</h3> --}}
                    <h3 class="title">Proyectos</h3>
                </div>
                <!-- /Main Title -->
            </div>
        </div>

        {{-- Proyectos --}}
        <div class="container noright">
            <div class="roww">

                <div class="owl-carousel modal_items" data-from="portfolio" data-count="5">
                    <div class="item modal_item" data-index="1">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/AA.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/AA.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3><a href="#">Abarroterías Albamar</a></h3>
                        </div>
                        <div class="fn__hidden">
                            {{-- <p class="fn__cat">illustration, design</p> --}}
                            <h3 class="fn__title">Abarroterías Albamar</h3>
                            <div class="img_holder">
                                <img src="{{asset('img/empresas/AA.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/AA.png')}}"></div>
                            </div>
                            <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique sapien
                                malesuada. Proin sagittis maximus accumsan. Class aptent taciti sociosqu
                                ad litora torquent per conubia nostra, per inceptos himenaeos. Lorem
                                ipsum dolor sit amet, consectetur adipiscing elit. Quisque gravida quam
                                sit amet elit varius tempor. Pellentesque purus eros, blandit eu mollis
                                vel, commodo eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                consectetur efficitur ut quis est. Proin posuere orci eget vulputate
                                fringilla. Curabitur placerat massa eget efficitur cursus. Sed
                                sollicitudin rhoncus blandit. Nam accumsan vestibulum enim. Sed rutrum
                                eu leo pellentesque lobortis. Integer ornare fringilla arcu, eu mattis
                                risus convallis in.</p>
                            <p class="fn__desc">Quisque dui metus, eleifend at enim ac, imperdiet
                                sagittis dolor. Vestibulum ipsum quam, feugiat non velit sit amet,
                                pulvinar varius nisl. Mauris tristique, ipsum sit amet lacinia congue,
                                mauris magna tempus nibh, in mollis eros enim a tortor. Morbi enim arcu,
                                tristique vitae mi nec, hendrerit pharetra metus. Phasellus id feugiat
                                purus. In vel elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc
                                sit amet libero sit amet lectus pellentesque sagittis. Curabitur blandit
                                ante quis erat dapibus viverra. Maecenas consequat pulvinar pulvinar.
                                Donec in aliquam arcu. Donec eu laoreet dolor. Ut nisi lectus, pulvinar
                                ac mattis quis, pretium ac nulla. Morbi sed ligula ultrices, ornare
                                mauris id, auctor arcu. Sed pellentesque ex sed erat faucibus, ultrices
                                vehicula ex dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                porttitor eu.</p>
                            <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio viverra
                                id. In nec finibus elit. Nullam ac sodales nunc, vel sagittis elit. Ut
                                condimentum ex ipsum, eu ornare odio aliquam eu. Ut iaculis eros quam,
                                eu bibendum tellus convallis quis. Donec sapien risus, consequat ut
                                magna nec, interdum porta nisl. Vivamus pulvinar hendrerit finibus. Nunc
                                molestie lacinia risus, id mattis nunc euismod ac. Nam eu orci felis.
                                Quisque ut elementum quam. Vivamus pulvinar nisi nunc, ut faucibus
                                turpis tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                faucibus metus id feugiat sodales. Sed eu ligula eget quam ultricies
                                tincidunt. Morbi sodales nunc ultrices justo pellentesque, ac mattis mi
                                sagittis. Morbi ut consectetur neque.</p>
                        </div>
                    </div>
                    <div class="item modal_item" data-index="2">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/ach.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/ach.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3><a href="#">Acción Contra el Hambre</a></h3>
                        </div>
                        <div class="fn__hidden">
                            {{-- <p class="fn__cat">illustration, design</p> --}}
                            <h3 class="fn__title">Acción Contra el Hambre</h3>
                            <div class="img_holder">
                                <img src="{{asset('img/empresas/ach.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/ach.png')}}"></div>
                            </div>
                            <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique sapien
                                malesuada. Proin sagittis maximus accumsan. Class aptent taciti sociosqu
                                ad litora torquent per conubia nostra, per inceptos himenaeos. Lorem
                                ipsum dolor sit amet, consectetur adipiscing elit. Quisque gravida quam
                                sit amet elit varius tempor. Pellentesque purus eros, blandit eu mollis
                                vel, commodo eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                consectetur efficitur ut quis est. Proin posuere orci eget vulputate
                                fringilla. Curabitur placerat massa eget efficitur cursus. Sed
                                sollicitudin rhoncus blandit. Nam accumsan vestibulum enim. Sed rutrum
                                eu leo pellentesque lobortis. Integer ornare fringilla arcu, eu mattis
                                risus convallis in.</p>
                            <p class="fn__desc">Quisque dui metus, eleifend at enim ac, imperdiet
                                sagittis dolor. Vestibulum ipsum quam, feugiat non velit sit amet,
                                pulvinar varius nisl. Mauris tristique, ipsum sit amet lacinia congue,
                                mauris magna tempus nibh, in mollis eros enim a tortor. Morbi enim arcu,
                                tristique vitae mi nec, hendrerit pharetra metus. Phasellus id feugiat
                                purus. In vel elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc
                                sit amet libero sit amet lectus pellentesque sagittis. Curabitur blandit
                                ante quis erat dapibus viverra. Maecenas consequat pulvinar pulvinar.
                                Donec in aliquam arcu. Donec eu laoreet dolor. Ut nisi lectus, pulvinar
                                ac mattis quis, pretium ac nulla. Morbi sed ligula ultrices, ornare
                                mauris id, auctor arcu. Sed pellentesque ex sed erat faucibus, ultrices
                                vehicula ex dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                porttitor eu.</p>
                            <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio viverra
                                id. In nec finibus elit. Nullam ac sodales nunc, vel sagittis elit. Ut
                                condimentum ex ipsum, eu ornare odio aliquam eu. Ut iaculis eros quam,
                                eu bibendum tellus convallis quis. Donec sapien risus, consequat ut
                                magna nec, interdum porta nisl. Vivamus pulvinar hendrerit finibus. Nunc
                                molestie lacinia risus, id mattis nunc euismod ac. Nam eu orci felis.
                                Quisque ut elementum quam. Vivamus pulvinar nisi nunc, ut faucibus
                                turpis tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                faucibus metus id feugiat sodales. Sed eu ligula eget quam ultricies
                                tincidunt. Morbi sodales nunc ultrices justo pellentesque, ac mattis mi
                                sagittis. Morbi ut consectetur neque.</p>
                        </div>
                    </div>
                    <div class="item modal_item" data-index="3">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/asedechi.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/asedechi.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3><a href="#">ASEDECHI</a></h3>
                        </div>
                        <div class="fn__hidden">
                            {{-- <p class="fn__cat">illustration, design</p> --}}
                            <h3 class="fn__title">ASEDECHI</h3>
                            <div class="img_holder">
                                <img src="{{asset('img/empresas/asedechi.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/asedechi.png')}}"></div>
                            </div>
                            <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique sapien
                                malesuada. Proin sagittis maximus accumsan. Class aptent taciti sociosqu
                                ad litora torquent per conubia nostra, per inceptos himenaeos. Lorem
                                ipsum dolor sit amet, consectetur adipiscing elit. Quisque gravida quam
                                sit amet elit varius tempor. Pellentesque purus eros, blandit eu mollis
                                vel, commodo eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                consectetur efficitur ut quis est. Proin posuere orci eget vulputate
                                fringilla. Curabitur placerat massa eget efficitur cursus. Sed
                                sollicitudin rhoncus blandit. Nam accumsan vestibulum enim. Sed rutrum
                                eu leo pellentesque lobortis. Integer ornare fringilla arcu, eu mattis
                                risus convallis in.</p>
                            <p class="fn__desc">Quisque dui metus, eleifend at enim ac, imperdiet
                                sagittis dolor. Vestibulum ipsum quam, feugiat non velit sit amet,
                                pulvinar varius nisl. Mauris tristique, ipsum sit amet lacinia congue,
                                mauris magna tempus nibh, in mollis eros enim a tortor. Morbi enim arcu,
                                tristique vitae mi nec, hendrerit pharetra metus. Phasellus id feugiat
                                purus. In vel elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc
                                sit amet libero sit amet lectus pellentesque sagittis. Curabitur blandit
                                ante quis erat dapibus viverra. Maecenas consequat pulvinar pulvinar.
                                Donec in aliquam arcu. Donec eu laoreet dolor. Ut nisi lectus, pulvinar
                                ac mattis quis, pretium ac nulla. Morbi sed ligula ultrices, ornare
                                mauris id, auctor arcu. Sed pellentesque ex sed erat faucibus, ultrices
                                vehicula ex dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                porttitor eu.</p>
                            <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio viverra
                                id. In nec finibus elit. Nullam ac sodales nunc, vel sagittis elit. Ut
                                condimentum ex ipsum, eu ornare odio aliquam eu. Ut iaculis eros quam,
                                eu bibendum tellus convallis quis. Donec sapien risus, consequat ut
                                magna nec, interdum porta nisl. Vivamus pulvinar hendrerit finibus. Nunc
                                molestie lacinia risus, id mattis nunc euismod ac. Nam eu orci felis.
                                Quisque ut elementum quam. Vivamus pulvinar nisi nunc, ut faucibus
                                turpis tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                faucibus metus id feugiat sodales. Sed eu ligula eget quam ultricies
                                tincidunt. Morbi sodales nunc ultrices justo pellentesque, ac mattis mi
                                sagittis. Morbi ut consectetur neque.</p>
                        </div>
                    </div>
                    <div class="item modal_item" data-index="4">
                        <div class="img_holder">
                            <img src="{{asset('img/empresas/COMFORT.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/COMFORT.png')}}"></div>
                        </div>
                        <div class="title_holder" align="center">
                            {{-- <p>illustration, design</p> --}}
                            <h3><a href="#">Comfort Dreams</a></h3>
                        </div>
                        <div class="fn__hidden">
                            {{-- <p class="fn__cat">illustration, design</p> --}}
                            <h3 class="fn__title">Comfort Dreams</h3>
                            <div class="img_holder">
                                <img src="{{asset('img/empresas/COMFORT.png')}}" alt="">
                            <div class="abs_img" data-bg-img="{{asset('img/empresas/COMFORT.png')}}"></div>
                            </div>
                            <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique sapien
                                malesuada. Proin sagittis maximus accumsan. Class aptent taciti sociosqu
                                ad litora torquent per conubia nostra, per inceptos himenaeos. Lorem
                                ipsum dolor sit amet, consectetur adipiscing elit. Quisque gravida quam
                                sit amet elit varius tempor. Pellentesque purus eros, blandit eu mollis
                                vel, commodo eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                consectetur efficitur ut quis est. Proin posuere orci eget vulputate
                                fringilla. Curabitur placerat massa eget efficitur cursus. Sed
                                sollicitudin rhoncus blandit. Nam accumsan vestibulum enim. Sed rutrum
                                eu leo pellentesque lobortis. Integer ornare fringilla arcu, eu mattis
                                risus convallis in.</p>
                            <p class="fn__desc">Quisque dui metus, eleifend at enim ac, imperdiet
                                sagittis dolor. Vestibulum ipsum quam, feugiat non velit sit amet,
                                pulvinar varius nisl. Mauris tristique, ipsum sit amet lacinia congue,
                                mauris magna tempus nibh, in mollis eros enim a tortor. Morbi enim arcu,
                                tristique vitae mi nec, hendrerit pharetra metus. Phasellus id feugiat
                                purus. In vel elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc
                                sit amet libero sit amet lectus pellentesque sagittis. Curabitur blandit
                                ante quis erat dapibus viverra. Maecenas consequat pulvinar pulvinar.
                                Donec in aliquam arcu. Donec eu laoreet dolor. Ut nisi lectus, pulvinar
                                ac mattis quis, pretium ac nulla. Morbi sed ligula ultrices, ornare
                                mauris id, auctor arcu. Sed pellentesque ex sed erat faucibus, ultrices
                                vehicula ex dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                porttitor eu.</p>
                            <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio viverra
                                id. In nec finibus elit. Nullam ac sodales nunc, vel sagittis elit. Ut
                                condimentum ex ipsum, eu ornare odio aliquam eu. Ut iaculis eros quam,
                                eu bibendum tellus convallis quis. Donec sapien risus, consequat ut
                                magna nec, interdum porta nisl. Vivamus pulvinar hendrerit finibus. Nunc
                                molestie lacinia risus, id mattis nunc euismod ac. Nam eu orci felis.
                                Quisque ut elementum quam. Vivamus pulvinar nisi nunc, ut faucibus
                                turpis tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                faucibus metus id feugiat sodales. Sed eu ligula eget quam ultricies
                                tincidunt. Morbi sodales nunc ultrices justo pellentesque, ac mattis mi
                                sagittis. Morbi ut consectetur neque.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- /Portfolio Section -->



    



    <!-- Customers Section -->
    {{-- <section id="customers">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <h3 class="subtitle">Customers</h3>
                    <h3 class="title">Happy People</h3>
                </div>
                <!-- /Main Title -->


                <!-- Partners -->
                <div class="resumo_fn_partners">
                    <ul>
                        <li><a href="https://envato.com/" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/1.png')}}"
                                    alt=""></a></li>
                        <li><a href="https://frenify.com/" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/2.png')}}"
                                    alt=""></a></li>
                        <li><a href="https://themeforest.net/item/rewall-personal-portfolio-template/34316546"
                                target="_blank"><img src="{{asset('singulartemplate/html/img/partners/3.png')}}" alt=""></a></li>
                        <li><a href="https://themeforest.net/item/artemiz-blog-podcast-wordpress-theme/28455063"
                                target="_blank"><img src="{{asset('singulartemplate/html/img/partners/4.png')}}" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/5.png')}}" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/6.png')}}" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/7.png')}}" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="{{asset('singulartemplate/html/img/partners/8.png')}}" alt=""></a></li>
                    </ul>
                </div>
                <!-- /Partners -->


                <!-- Testimonials -->
                <div class="resumo_fn_testimonials">
                    <div class="my__nav">
                        <a href="#" class="prev"><span></span></a>
                        <a href="#" class="next"><span></span></a>
                    </div>
                    <div class="owl-carousel">
                        <div class="item">
                            <div class="title_holder">
                                <p class="desc">“ They really nailed it. This is one of the best themes
                                    I have seen in a long time. Very nice design with lots of
                                    customization available. Many of my clients have chosen this theme
                                    for their portfolio sites. ”</p>
                                <h3 class="title">Albert Walker</h3>
                                <h3 class="subtitle">Freelancer &amp; Designer</h3>
                            </div>
                        </div>
                        <div class="item">
                            <div class="title_holder">
                                <p class="desc">“ This was exactly what I needed for my portfolio, and
                                    it looks great. I had a couple issues that support helped
                                    troubleshoot both via email and on the comments, which definitely
                                    made it worth the price. I'm very pleased with this purchase. ”</p>
                                <h3 class="title">Anna Barbera</h3>
                                <h3 class="subtitle">Photographer</h3>
                            </div>
                        </div>
                        <div class="item">
                            <div class="title_holder">
                                <p class="desc">“ Had a problem with the layout after Installation-
                                    found no approach. The support reacted quickly and competently. And
                                    solved the problem between Elementor and a WordPress update. Great!
                                    ”</p>
                                <h3 class="title">Dana Atkins</h3>
                                <h3 class="subtitle">Customer</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Testimonials -->



            </div>
        </div>
    </section> --}}
    <!-- /Customers Section -->




    <!-- News Section -->
    <section id="equipo">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    {{-- <h3 class="subtitle">News &amp; Tips</h3> --}}
                    <h3 class="title">Equipo</h3>
                </div>
                <!-- /Main Title -->


                <!-- Blog List -->
                <div class="resumo_fn_blog_list">

                    <ul class="modal_items" data-from="blog" data-count="5">
                        <li>
                            <div class="item modal_item" data-index="1">
                                <div class="img_holder">
                                    <img src="{{asset('img/equipo/edgar.png')}}" alt="">
                                    <div class="abs_img" data-bg-img="{{asset('img/equipo/edgar.png')}}"></div>
                                </div>
                                <div class="title_holder">
                                    <p>Director General</p>
                                    <h3><a href="#">Edgar</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">Director General</p>
                                    <h3 class="fn__title">Edgar</h3>
                                    <div class="img_holder">
                                        <img src="{{asset('img/equipo/edgar.png')}}" alt="">
                                        <div class="abs_img" data-bg-img="{{asset('img/equipo/edgar.png')}}"></div>
                                    </div>
                                    <h3>PROFESIÓN</h3>
                                    <p class="fn__desc">Mercadólogo publicista con especialización en ingeniería administrativa y maestría en administración industrial y gestión de proyectos.</p>
                                    <h3>EXPERIENCIA GENERAL</h3>
                                    <p class="fn__desc">• 9 años de experiencia en el fortalecimiento de procesos de desarrollo empresarial con enfoque publicitario y creación de marcas, áreas comerciales en Guatemala, Centroamérica y Estados Unidos entre otros. (2013 a la fecha)</p>
                                    <p class="fn__desc">• 5 años como formador en medios digitales y plataformas virtuales para redes empresariales y empresas comunitarias en Guatemala, y Centroamérica a través de proyectos de cooperación internacional.</p>
                                    <p class="fn__desc">• 3 años como docente universitario en cursos de marketing, coaching, y administración de procesos. en Quetzaltenango.</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="2">
                                <div class="img_holder">
                                    <img src="{{asset('img/equipo/lucia.png')}}" alt="">
                                    <div class="abs_img" data-bg-img="{{asset('img/equipo/lucia.png')}}"></div>
                                </div>
                                <div class="title_holder">
                                    <p>Directora Administrativa</p>
                                    <h3><a href="#">Lucia</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">Directora Administrativa</p>
                                    <h3 class="fn__title">Lucia</h3>
                                    <div class="img_holder">
                                        <img src="{{asset('img/equipo/lucia.png')}}" alt="">
                                        <div class="abs_img" data-bg-img="{{asset('img/equipo/lucia.png')}}"></div>
                                    </div>
                                    <h3>PROFESIÓN</h3>
                                    <p class="fn__desc">Psicóloga Industrial / organizacional, con maestría en gestión y desarrollo estratégico del talento humano</p>
                                    <h3>EXPERIENCIA GENERAL</h3>
                                    <p class="fn__desc">• 7 años de experiencia en planificación y ejecución de procesos de gestión del talento humano para empresa privada y organizaciones publico/privadas.</p>
                                    <p class="fn__desc">• Encargada de vínculos de comunicación con mujeres y actores comunitarios para sensibilización y contexto de procesos de formación</p>
                                    <p class="fn__desc">• Formadora de Habilidades administrativas en mujeres parte de empresas comunitarias en programas de cooperación Internacional.</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="3">
                                <div class="img_holder">
                                    <img src="{{asset('img/equipo/javier.png')}}" alt="">
                                    <div class="abs_img" data-bg-img="{{asset('img/equipo/javier.png')}}"></div>
                                </div>
                                <div class="title_holder">
                                    <p>Director Creativo</p>
                                    <h3><a href="#">Javier</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">Director Creativo</p>
                                    <h3 class="fn__title">Javier</h3>
                                    <div class="img_holder">
                                        <img src="{{asset('img/equipo/javier.png')}}" alt="">
                                        <div class="abs_img" data-bg-img="{{asset('img/equipo/javier.png')}}"></div>
                                    </div>
                                    <h3>PROFESIÓN</h3>
                                    <p class="fn__desc">Licenciado en Diseño Gráfico y Publicidad</p>
                                    <h3>EXPERIENCIA GENERAL</h3>
                                    <p class="fn__desc">• 8 años de experiencia en desarrollo de marcas y diseño grafico en general trabajando para marcas a nivel Centroamérica como Helvetas, USAID, DR-CAFTA entre otros.</p>
                                    <p class="fn__desc">• 12 años de experiencia como caricaturista habiendo realizado trabajos para artistas nacionales e internacionales, publicaciones en revistas a nivel nacional, creación de personajes para marcas comerciales en Centroamérica.</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="4">
                                <div class="img_holder">
                                    <img src="{{asset('img/equipo/osmar.png')}}" alt="">
                                    <div class="abs_img" data-bg-img="{{asset('img/equipo/osmar.png')}}"></div>
                                </div>
                                <div class="title_holder">
                                    <p>Filmmaker</p>
                                    <h3><a href="#">Osmar</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">Filmmaker</p>
                                    <h3 class="fn__title">Osmar</h3>
                                    <div class="img_holder">
                                        <img src="{{asset('img/equipo/osmar.png')}}" alt="">
                                        <div class="abs_img" data-bg-img="{{asset('img/equipo/osmar.png')}}"></div>
                                    </div>
                                    <h3>PROFESIÓN</h3>
                                    <p class="fn__desc">Operativo de documentación audiovisual</p>
                                    <h3>EXPERIENCIA GENERAL</h3>
                                    <p class="fn__desc">• 3 años de experiencia en la realización de material audiovisual para marcas a nivel regional.</p>
                                    <p class="fn__desc">• 3 años de experiencia en vuelo de drones en situaciones de riesgo y zonas de difícil acceso.</p>
                                    <p class="fn__desc">• 2 años de experiencia realizando material audiovisual para proyectos de cooperación internacional.</p>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="clearfix"></div>

                    {{-- <div class="load_more">
                        <a href="#" data-done="Done" data-no="No more articles found">
                            <span class="text">Load More Articles</span>
                            <span class="fn__pulse">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </a>
                    </div> --}}

                </div>
                <!-- /Blog List -->


            </div>
        </div>
    </section>
    <!-- /News Section -->


    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="roww resumo_fn_contact">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <h3 class="subtitle">Contacto</h3>
                    <h3 class="title">Ponte en contacto</h3>
                    <p class="desc">Si tienes un proyecto envíanos un mensaje.</p>
                </div>
                <!-- /Main Title -->

                <!-- Contact Form -->
                <form class="contact_form" action="/" method="post" autocomplete="off"
                    data-email="contacto@singular.com.gt">

                    <!--
                            Don't remove below code in avoid to work contact form properly.
                            You can chance dat-success value with your one. It will be used when user will try to contact via contact form and will get success message.
                        -->
                    <div class="success" data-success="Your message has been received, we will contact you soon."></div>
                    <div class="empty_notice"><span>Por favor rellena los campos requeridos!</span></div>
                    <!-- -->

                    <div class="items_wrap">
                        <div class="items">
                            <div class="item half">
                                <div class="input_wrapper">
                                    <input id="name" type="text" />
                                    <span class="moving_placeholder">Nombre *</span>
                                </div>
                            </div>
                            <div class="item half">
                                <div class="input_wrapper">
                                    <input id="email" type="email" />
                                    <span class="moving_placeholder">Email *</span>
                                </div>
                            </div>
                            <div class="item">
                                <div class="input_wrapper">
                                    <input id="phone" type="text" />
                                    <span class="moving_placeholder">Teléfono *</span>
                                </div>
                            </div>
                            <div class="item">
                                <div class="input_wrapper">
                                    <textarea id="message"></textarea>
                                    <span class="moving_placeholder">Mensaje </span>
                                </div>
                            </div>
                            <div class="item">
                                <a id="send_message" href="#">Enviar Mensaje</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /Contact Form -->

                <!-- Contact Info -->
                <div class="resumo_fn_contact_info">
                    {{-- <p>Address</p>
                    <h3>69 Queen St, London, United Kingdom</h3> --}}
                    <p>Teléfonos</p>
                    <h3><a href="tel:+50237513802">(+502) 3751-3802</a></h3>
                    <h3><a href="tel:+50237513802">(+502) 3738-6504</a></h3>
                    <p>Correos</p>
                    <p><a class="fn__link" href="mailto:edgars@singular.com.gt">edgars@singular.com.gt</a>
                        <p><a class="fn__link" href="mailto:luciai@singular.com.gt">luciai@singular.com.gt</a>
                    </p>
                </div>
                <!-- /Contact Info -->

            </div>
        </div>
    </section>
    <!-- /Contact Section -->
@endsection
