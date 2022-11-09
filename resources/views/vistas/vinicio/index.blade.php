@extends('layouts.app')
@section('content')
    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <h3 class="subtitle">Introduction</h3>
                    <h3 class="title">UI/UX Designer</h3>
                    <p class="desc">I design and develop services for customers of all sizes,
                        specializing in creating stylish, modern websites, web services and online
                        stores.</p>
                    <img src="img/signature.png" alt="">
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
                    <h3 class="subtitle">About Me</h3>
                    <h3 class="title">Biography</h3>
                    <p class="desc">I'm a Freelancer Front-end Developer with over 12 years of
                        experience. I'm from San Francisco. I code and create web elements for amazing
                        people around the world. I like work with new people. New people are new
                        experiences.</p>
                </div>
                <!-- /Main Title -->

                <!-- About Information -->
                <div class="resumo_fn_about_info">
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
                            <a href="img/cv.jpg" download>
                                <span class="icon">
                                    <img src="svg/inbox.svg" alt="" class="fn__svg" />
                                    <img src="svg/arrow.svg" alt="" class="fn__svg arrow" />
                                </span>
                                <span>Download CV</span>
                            </a>
                        </div>
                        <!-- /Download CV Button -->
                    </div>
                </div>
                <!-- /About Information -->


                <!-- Tabs Shortcode -->
                <div class="resumo_fn_tabs">

                    <!-- Tab: Header -->
                    <div class="tab_header">
                        <ul>
                            <li class="active"><a href="#tab1">Experience</a></li>
                            <li><a href="#tab2">Education</a></li>
                            <li><a href="#tab3">Skills</a></li>
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
                                                <h5>Frenify LLC</h5>
                                                <span>( 2018 — Today )</span>
                                            </div>
                                            <h3>Sr. Front-end Engineer</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                <h5>Google LLC</h5>
                                                <span>( 2016 — 2018 )</span>
                                            </div>
                                            <h3>Front-end Developer</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item">
                                            <div class="item_top">
                                                <h5>Twitter LLC</h5>
                                                <span>( 2016 — 2011 )</span>
                                            </div>
                                            <h3>Graphic Designer</h3>
                                            <p>Adipisicing Lorem ipsum dolor sit amet, consectetur elit,
                                                sed do eiusmod tempor incididunt ut labore et dolore
                                                magna aliqua. </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Boxed List -->

                        </div>
                        <!-- /#1 tab content -->

                        <!-- #2 tab content -->
                        <div id="tab2" class="tab_item">

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

                        </div>
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
                    <h3 class="subtitle">Portfolio</h3>
                    <h3 class="title">Featured Projects</h3>
                </div>
                <!-- /Main Title -->
            </div>
        </div>

        <div class="container noright">
            <div class="roww">

                <div class="owl-carousel modal_items" data-from="portfolio" data-count="5">
                    <div class="item modal_item" data-index="1">
                        <div class="img_holder">
                            <img src="img/thumb/square.jpg" alt="">
                            <div class="abs_img" data-bg-img="img/portfolio/1.jpg"></div>
                        </div>
                        <div class="title_holder">
                            <p>illustration, design</p>
                            <h3><a href="#">Sweet Cherry</a></h3>
                        </div>
                        <div class="fn__hidden">
                            <p class="fn__cat">illustration, design</p>
                            <h3 class="fn__title">Sweet Cherry</h3>
                            <div class="img_holder">
                                <img src="img/thumb/square.jpg" alt="">
                                <div class="abs_img" data-bg-img="img/portfolio/1.jpg"></div>
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
                            <img src="img/thumb/square.jpg" alt="">
                            <div class="abs_img" data-bg-img="img/portfolio/2.jpg"></div>
                        </div>
                        <div class="title_holder">
                            <p>web, mobile, online</p>
                            <h3><a href="#">Delicious Fruit</a></h3>
                        </div>
                        <div class="fn__hidden">
                            <p class="fn__cat">web, mobile, online</p>
                            <h3 class="fn__title">Delicious Fruit</h3>
                            <div class="img_holder">
                                <img src="img/thumb/square.jpg" alt="">
                                <div class="abs_img" data-bg-img="img/portfolio/2.jpg"></div>
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
                            <img src="img/thumb/square.jpg" alt="">
                            <div class="abs_img" data-bg-img="img/portfolio/3.jpg"></div>
                        </div>
                        <div class="title_holder">
                            <p>design, vector</p>
                            <h3><a href="#">Blue Lemon</a></h3>
                        </div>
                        <div class="fn__hidden">
                            <p class="fn__cat">design, vector</p>
                            <h3 class="fn__title">Blue Lemon</h3>
                            <div class="img_holder">
                                <img src="img/thumb/square.jpg" alt="">
                                <div class="abs_img" data-bg-img="img/portfolio/3.jpg"></div>
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
                            <img src="img/thumb/square.jpg" alt="">
                            <div class="abs_img" data-bg-img="img/portfolio/4.jpg"></div>
                        </div>
                        <div class="title_holder">
                            <p>mobile, design</p>
                            <h3><a href="#">Yellow Phone</a></h3>
                        </div>
                        <div class="fn__hidden">
                            <p class="fn__cat">mobile, design</p>
                            <h3 class="fn__title">Yellow Phone</h3>
                            <div class="img_holder">
                                <img src="img/thumb/square.jpg" alt="">
                                <div class="abs_img" data-bg-img="img/portfolio/4.jpg"></div>
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
                    <div class="item modal_item" data-index="5">
                        <div class="img_holder">
                            <img src="img/thumb/square.jpg" alt="">
                            <div class="abs_img" data-bg-img="img/portfolio/5.jpg"></div>
                        </div>
                        <div class="title_holder">
                            <p>mobile, design</p>
                            <h3><a href="#">Ice Cream</a></h3>
                        </div>
                        <div class="fn__hidden">
                            <p class="fn__cat">mobile, design</p>
                            <h3 class="fn__title">Ice Cream</h3>
                            <div class="img_holder">
                                <img src="img/thumb/square.jpg" alt="">
                                <div class="abs_img" data-bg-img="img/portfolio/5.jpg"></div>
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



    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <h3 class="subtitle">Services</h3>
                    <h3 class="title">What I Do</h3>
                    <p class="desc">I help ambitious businesses like yours generate more profits by
                        building awareness, driving web traffic, connecting with customers and growing
                        overall sales.</p>
                </div>
                <!-- /Main Title -->


                <!-- Services List -->
                <div class="resumo_fn_service_list">
                    <ul>
                        <li>
                            <div class="item">
                                <div class="item_left">
                                    <h3>Brand Consultant</h3>
                                    <p>I build brands through cultural insights &amp; strategic vision.
                                        Custom crafted business solutions.</p>
                                </div>
                                <div class="item_right">
                                    <p>Starts from</p>
                                    <h3>$599</h3>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="item_left">
                                    <h3>Global Marketing</h3>
                                    <p>Custom marketing solutions. Get your business on the next level.
                                        We provide worldwide marketing.</p>
                                </div>
                                <div class="item_right">
                                    <p>Starts from</p>
                                    <h3>$399</h3>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="item_left">
                                    <h3>UI/UX Solutions</h3>
                                    <p>Design direction for business. Get your business on the next
                                        level. We help to create great experiences.</p>
                                </div>
                                <div class="item_right">
                                    <p>Starts from</p>
                                    <h3>$499</h3>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- /Services List -->

            </div>
        </div>
    </section>
    <!-- /Services Section -->



    <!-- Customers Section -->
    <section id="customers">
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
                        <li><a href="https://envato.com/" target="_blank"><img src="img/partners/1.png"
                                    alt=""></a></li>
                        <li><a href="https://frenify.com/" target="_blank"><img src="img/partners/2.png"
                                    alt=""></a></li>
                        <li><a href="https://themeforest.net/item/rewall-personal-portfolio-template/34316546"
                                target="_blank"><img src="img/partners/3.png" alt=""></a></li>
                        <li><a href="https://themeforest.net/item/artemiz-blog-podcast-wordpress-theme/28455063"
                                target="_blank"><img src="img/partners/4.png" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="img/partners/5.png" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="img/partners/6.png" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="img/partners/7.png" alt=""></a></li>
                        <li><a href="#" target="_blank"><img src="img/partners/8.png" alt=""></a></li>
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
    </section>
    <!-- /Customers Section -->




    <!-- News Section -->
    <section id="news">
        <div class="container">
            <div class="roww">

                <!-- Main Title -->
                <div class="resumo_fn_main_title">
                    <h3 class="subtitle">News &amp; Tips</h3>
                    <h3 class="title">Latest Articles</h3>
                </div>
                <!-- /Main Title -->


                <!-- Blog List -->
                <div class="resumo_fn_blog_list">

                    <ul class="modal_items" data-from="blog" data-count="5">
                        <li>
                            <div class="item modal_item" data-index="1">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/1.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>September 22, 2021</p>
                                    <h3><a href="#">Five Solid Evidences Attending Design Is Good For
                                            Your Career Development.</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">September 22, 2021</p>
                                    <h3 class="fn__title">Five Solid Evidences Attending Design Is Good
                                        For Your Career Development.</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/1.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="2">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/2.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>September 17, 2021</p>
                                    <h3><a href="#">Ten Mind-Blowing Reasons Why Design Is Using This
                                            Technique For Exposure.</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">September 17, 2021</p>
                                    <h3 class="fn__title">Ten Mind-Blowing Reasons Why Design Is Using
                                        This Technique For Exposure.</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/2.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="3">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/3.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>September 04, 2021</p>
                                    <h3><a href="#">I Will Tell You The Truth About Design In The Next
                                            60 Seconds.</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">September 04, 2021</p>
                                    <h3 class="fn__title">I Will Tell You The Truth About Design In The
                                        Next 60 Seconds.</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/3.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item modal_item" data-index="4">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/4.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>August 18, 2021</p>
                                    <h3><a href="#">What You Know About Design And What You Don't Know
                                            About Design.</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">August 18, 2021</p>
                                    <h3 class="fn__title">What You Know About Design And What You Don't
                                        Know About Design.</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/4.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="be_animated">
                            <div class="item modal_item" data-index="5">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/5.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>August 15, 2021</p>
                                    <h3><a href="#">Forgive Yourself for Not Being Perfect. Then Do It
                                            Again Tomorrow.</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">August 15, 2021</p>
                                    <h3 class="fn__title">Forgive Yourself for Not Being Perfect. Then
                                        Do It Again Tomorrow.</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/5.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="be_animated">
                            <div class="item modal_item" data-index="6">
                                <div class="img_holder">
                                    <img src="img/thumb/square.jpg" alt="">
                                    <div class="abs_img" data-bg-img="img/blog/6.jpg"></div>
                                </div>
                                <div class="title_holder">
                                    <p>August 01, 2021</p>
                                    <h3><a href="#">Why Decorating Your Home Is Good for Your Mental
                                            Health</a></h3>
                                </div>
                                <div class="fn__hidden">
                                    <p class="fn__cat">August 01, 2021</p>
                                    <h3 class="fn__title">Why Decorating Your Home Is Good for Your
                                        Mental Health</h3>
                                    <div class="img_holder">
                                        <img src="img/thumb/square.jpg" alt="">
                                        <div class="abs_img" data-bg-img="img/blog/6.jpg"></div>
                                    </div>
                                    <p class="fn__desc">Sed ornare tellus a odio bibendum, at tristique
                                        sapien malesuada. Proin sagittis maximus accumsan. Class aptent
                                        taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit. Quisque gravida quam sit amet elit varius
                                        tempor. Pellentesque purus eros, blandit eu mollis vel, commodo
                                        eget orci. Proin vel hendrerit ex. Vivamus ut ex at nunc
                                        consectetur efficitur ut quis est. Proin posuere orci eget
                                        vulputate fringilla. Curabitur placerat massa eget efficitur
                                        cursus. Sed sollicitudin rhoncus blandit. Nam accumsan
                                        vestibulum enim. Sed rutrum eu leo pellentesque lobortis.
                                        Integer ornare fringilla arcu, eu mattis risus convallis in.</p>
                                    <p class="fn__desc">Quisque dui metus, eleifend at enim ac,
                                        imperdiet sagittis dolor. Vestibulum ipsum quam, feugiat non
                                        velit sit amet, pulvinar varius nisl. Mauris tristique, ipsum
                                        sit amet lacinia congue, mauris magna tempus nibh, in mollis
                                        eros enim a tortor. Morbi enim arcu, tristique vitae mi nec,
                                        hendrerit pharetra metus. Phasellus id feugiat purus. In vel
                                        elit eu lacus ultrices feugiat. Etiam at aliquet mi. Nunc sit
                                        amet libero sit amet lectus pellentesque sagittis. Curabitur
                                        blandit ante quis erat dapibus viverra. Maecenas consequat
                                        pulvinar pulvinar. Donec in aliquam arcu. Donec eu laoreet
                                        dolor. Ut nisi lectus, pulvinar ac mattis quis, pretium ac
                                        nulla. Morbi sed ligula ultrices, ornare mauris id, auctor arcu.
                                        Sed pellentesque ex sed erat faucibus, ultrices vehicula ex
                                        dapibus. Aenean venenatis metus eros, vel faucibus lorem
                                        porttitor eu.</p>
                                    <p class="fn__desc">Sed porttitor augue erat, vitae convallis odio
                                        viverra id. In nec finibus elit. Nullam ac sodales nunc, vel
                                        sagittis elit. Ut condimentum ex ipsum, eu ornare odio aliquam
                                        eu. Ut iaculis eros quam, eu bibendum tellus convallis quis.
                                        Donec sapien risus, consequat ut magna nec, interdum porta nisl.
                                        Vivamus pulvinar hendrerit finibus. Nunc molestie lacinia risus,
                                        id mattis nunc euismod ac. Nam eu orci felis. Quisque ut
                                        elementum quam. Vivamus pulvinar nisi nunc, ut faucibus turpis
                                        tincidunt eget. Fusce nec ex quis odio laoreet consequat. Duis
                                        faucibus metus id feugiat sodales. Sed eu ligula eget quam
                                        ultricies tincidunt. Morbi sodales nunc ultrices justo
                                        pellentesque, ac mattis mi sagittis. Morbi ut consectetur neque.
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="clearfix"></div>

                    <div class="load_more">
                        <a href="#" data-done="Done" data-no="No more articles found">
                            <span class="text">Load More Articles</span>
                            <span class="fn__pulse">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </a>
                    </div>

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
                    <h3 class="subtitle">Contact</h3>
                    <h3 class="title">Get In Touch</h3>
                    <p class="desc">If you have any suggestion, project or even you want to say “hello”,
                        please fill out the form below and I will reply you shortly.</p>
                </div>
                <!-- /Main Title -->

                <!-- Contact Form -->
                <form class="contact_form" action="/" method="post" autocomplete="off"
                    data-email="frenifyteam@gmail.com">

                    <!--
                            Don't remove below code in avoid to work contact form properly.
                            You can chance dat-success value with your one. It will be used when user will try to contact via contact form and will get success message.
                        -->
                    <div class="success" data-success="Your message has been received, we will contact you soon."></div>
                    <div class="empty_notice"><span>Please Fill Required Fields!</span></div>
                    <!-- -->

                    <div class="items_wrap">
                        <div class="items">
                            <div class="item half">
                                <div class="input_wrapper">
                                    <input id="name" type="text" />
                                    <span class="moving_placeholder">Name *</span>
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
                                    <span class="moving_placeholder">Phone</span>
                                </div>
                            </div>
                            <div class="item">
                                <div class="input_wrapper">
                                    <textarea id="message"></textarea>
                                    <span class="moving_placeholder">Message</span>
                                </div>
                            </div>
                            <div class="item">
                                <a id="send_message" href="#">Send Message</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /Contact Form -->

                <!-- Contact Info -->
                <div class="resumo_fn_contact_info">
                    <p>Address</p>
                    <h3>69 Queen St, London, United Kingdom</h3>
                    <p>Phone</p>
                    <h3><a href="tel:+7068980751">(+706) 898-0751</a></h3>
                    <p><a class="fn__link" href="mailto:frenifyteam@gmail.com">frenifyteam@gmail.com</a>
                    </p>
                </div>
                <!-- /Contact Info -->

            </div>
        </div>
    </section>
    <!-- /Contact Section -->
@endsection
