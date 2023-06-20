

@extends('layouts.front_master')
@section('content')
        <div class="slider-main">

          
            <div id="slider" class="nivoSlider">
                <img src="{{asset('/wp-content/themes/prayer-pro/images/slides/slider1.jpg')}}" alt="" title="#slidecaption1" />
                <img src="{{asset('wp-content/themes/prayer-pro/images/slides/slider2.jpg')}}" alt="" title="#slidecaption2" />
                <img src="{{asset('wp-content/themes/prayer-pro/images/slides/slider3.jpg')}}" alt="" title="#slidecaption3" /> </div>

            <div id="slidecaption1" class="nivo-html-caption">
                <h5>Tovuti ya kanisa</h5>
                <h2>Tumsifu yesu kristo</h2>
                <p>Karibu katika tovuti ya kanisa, kwa taarifa, historia na matangazo mbalimbali tumsifu Yesu Kristo!</p>
            </div>
            <div id="slidecaption2" class="nivo-html-caption">

                <h5>Matoleo Kidigitali</h5>
                <h2>Unaweza kutuma maombi ya misa parokiani, na matoleo kwa simu yako.</h2>
                <p>Inapotokea umechelewa kufika parokiani na unahitaji kutuma maombi ya misa utakamilisha kwa kutumia mfumo huu. Tumsifu Yesu Kristo! </p>
            </div>
            <div id="slidecaption3" class="nivo-html-caption">
                <h5>Matukio na Picha</h5>
                <h2>Fuatulia Taarifa, na Pakua picha hapa</h2>
                <p>Kwa taarifa na picha za matukio mbalimbali ya Parokiani utapata hapa na hivo kuweza kupakua (download) kwa simu yako. Tumsifu Yesu Kristo! </p>

            </div>
            <div class="slidebottom"></div>
        </div>
    
        <!-- slider -->

        <section id="pagearea">
            <div class="container">
                <div class="pagebox_left ">

                    <h3>@if($sahihisha_kanda->name) {{$sahihisha_kanda->name}} @else Kanda @endif</h3>
                    <p>Huu ni utaratibu wa kuzigawanya kanda katika makundi ya kuzi simamia. Hapa utana orodha ya kanda zote. Na taarifa zake ambazo viongozi watakuwa wameziweka. Tumsifu Yesu Kristo!</p>

                    <a class="green_button" href="#">Ona zaidi</a>

                    <div class="clear"></div>

                </div>
                <!--.pagebox_left-->

                @if($kanda_zetu->isNotEmpty())
                <div class="pagebox_right">
                    @foreach ($kanda_zetu as $kanda) @php $number = $loop->index+1; @endphp @if($number%2==0)
                    <div class="fourcolbx last_column">
                        <div class="thumbbx">
                            <a href="#"><img src="{{asset('/wp-content/themes/prayer-pro/images/services_icon3.png')}}" alt="" /></a>
                        </div>
                        <h3><a href="#">{{$kanda->jina_la_kanda}}</a> </h3>
                    </div>
                    @else
                    <div class="fourcolbx">
                        <div class="thumbbx">
                            <a href="#"><img src="{{asset('/wp-content/themes/prayer-pro/images/services_icon3.png')}}" alt="" /></a>
                        </div>
                        <h3><a href="#">{{$kanda->jina_la_kanda}}</a> </h3>
                    </div>
                    @endif @endforeach
                </div>
                @else
                <p>Ukurasa huu utazungumzia Taarifa kuhusu @if($sahihisha_kanda->name) {{$sahihisha_kanda->name}} @else kanda @endif zetu..</p>
                @endif
                <!--.pagebox_right-->
                <div class="clear"></div>

            </div>
            <!-- .container -->
        </section>
        <!-- #pagearea -->
        <section style="background-color:#ffffff; " id="section1" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Vyama vya kitume</h2>
                    <p>
                        <div class="subtitle" style="font-size:16px; color:#535353; text-align:center;">Nunc commodo lacinia ipsum, scelerisque cursus libero ullamcorper sed. Praesent fermentum nisl ac neque tristique porttitor. Sed lectus lacus, vestibulum at fermentum.</div><br /> @if($mashirikas->isNotEmpty()) @foreach ($mashirikas
                        as $row)
                        <div class="pp_servicesbx">
                            <a href="#">
                                <div class="content-overlay"></div>
                                @if($row->getFirstMediaUrl('photo'))
                                <img class="card-img-top" src="{{$row->getFirstMediaUrl('photo')}}" alt="Picha ya shirika {{$row->id}}"> @else
                                <img src="{{asset('wp-content/themes/prayer-pro/images/services-image3.jpg')}}" /> @endif
                                <div class="content-details fadeIn-bottom">
                                    <h3>{{$row->jina_la_kikundi}}</h3>
                                    <p>{{$row->maoni}}.</p>
                                    <a class="green_button" href="#">Ona zaidi</a>
                                </div>
                            </a>
                        </div>
                        @endforeach @endif
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#f4f4f4; " id="jumuiya" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Jumuiya zetu</h2>
                    <div class="welcome_leftbox">
                        <h3>Jumuiya</h3>
                        <p>Donec in metus lecnteger vulputate porta elit, fringilla mollis maluctus vel. Interdum et malesuada fame ac ante ipsum primfauci. Pellentesque in aliquam enim, quis lobortis arcu Nullam at pulvinar quam. porttitor et turpis nec
                            viverra. Aliquauctor et nisi ut posuere. Suspendiss in nulla quis orci rhoncus rutruquis in odio. Phasellus lorem tortor.
                            <div class="custombtn" style="text-align:left">
                                <a class="morebutton" href="#" target="">Soma zaidi</a>
                            </div>
                    </div>
                    <div class="welcome_righbox">

                        @if($jumuiya_zetu->isNotEmpty()) @foreach ($jumuiya_zetu as $jumuiya) @php $number = $loop->index+1; @endphp @if($number%3==0)

                        <div class="wel3box_services last">
                            <div class="welcome_thumb">
                                <a href="#"><img src="wp-content/themes/prayer-pro/images/welcome_img03.jpg"></a>
                            </div>
                            <div class="wel3box_desc">
                                <h6>{{$jumuiya->jina_la_jumuiya}}</h6>
                            </div>
                        </div>
                        @else
                        <div class="wel3box_services">
                            <div class="welcome_thumb">
                                <a href="#"><img src="wp-content/themes/prayer-pro/images/welcome_img02.jpg"></a>
                            </div>
                            <div class="wel3box_desc">
                                <h6>{{$jumuiya->jina_la_jumuiya}}</h6>
                            </div>
                        </div>
                        @endif @endforeach @else
                        <p>Ukurasa huu utatoa muhtasari wa Taarifa kuhusu jumuiya zetu..</p>
                        @endif




                    </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#303030; background-image:url(wp-content/themes/prayer-pro/images/upcomingevent-bg.jpg); background-repeat:no-repeat; background-position: center top; background-attachment:fixed; background-size:cover; " id="matukio"
            class="menu_page">
            <div class="container">
                <div class="">
                    <div class="upcoming_eventbx">
                        <h4 class="orange_strip">Matukio yajayo</h4>
                        <h3>Kutembea kwa njia sahihi ya mungu</h3>
                        <p>Nunc commodo lacinia ipsum, scelerisque cursus libero ullamcorper sed. Praesent fermentum nisl ac neque tristique porttitor.
                            <div class="eventinfo">
                                <i class="far fa-clock"></i>
                                <span>Suturday 12-25-2020 8:00 to 11:00 am</span>
                            </div>
                            <div class="eventinfo">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Tanzania</span>
                            </div>
                    </div>
                    <div class="event_counter">
                        </p>
                        <h3>Tukio litaanza</h3>
                        <p>
                            <script>
                                CountDownTimer("2023/12/25", "countdown1");
                            </script>
                            <div id="countdown1" style="color:#ffffff;"></div>
                    </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#f4f4f4; " id="matangazo" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Matangazo</h2>

                    @if($matangazo->isNotEmpty()) @foreach ($matangazo as $tangazo)
                    <div class="our_event_list">
                        <div class="event_common event_imagebox">
                            <div class="eventthumb">
                                <a href="event/birthday-party/index.html"><img src="wp-content/uploads/2019/08/event_img1.jpg" /></a>
                            </div>
                        </div>

                        <div class="event_common event_date">
                            <div class="day_month">
                                <h4>{{Carbon\Carbon::parse($tangazo->tarehe)->format("F j, Y")}}</h4>
                            </div>
                        </div>
                        <div class="event_common event_infodetailsbox">
                            <div class="eventtexttitle">
                                <a href="event/birthday-party/index.html">
                                    <p> <img src="{{asset('images/new.gif')}}">{{$tangazo->kichwa}}</p>
                                </a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="event_common event_readmore">
                            <a class="green_button" href="#" target="_blank">Soma zaidi</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    @endforeach @else
                    <p>Ukurasa wa matangazo mbalimbali ya kanisa, maelezo mafupi kuhusu matangazo..</p>
                    @endif

                    <div class="clear"></div>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#f4f4f4; " id="misa" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Huduma za Kanisa</h2>
                    <p>
                        <div class="subtitle" style="font-size:16px; color:#535353; text-align:center;">Ukurasa huu unaonyesha ratiba zote za Misa,Maungamo</div><br />
                        <div class="threecolumn-news">
                            <div class="news-box  ">
                                <div class="news-thumb">
                                    <a href="2019/08/29/aliquam-placerat-tellus-ac-laoreet-euismod/index.html"><img src="wp-content/uploads/2019/08/gallery03.jpg" alt=" " /></a>
                                </div>
                                <div class="newsdesc">
                                    <div class="postdt"><span>{{date('Y')}}</span>{{date('F j', strtotime('sunday this week'))}}</div>
                                    <div class="PostMeta">

                                    </div>
                                    <h6>
                                        <a href="2019/08/29/aliquam-placerat-tellus-ac-laoreet-euismod/index.html"></a>
                                    </h6>
                                    <h6><a href="2019/04/23/interdum-et-malesuada-fames-ac-ante-ipsum-primis/index.html">Ratiba za Misa.</a></h6>
                                    <p>Misa ya kwanza&#8230;</p>
                                    <p>Misa ya Pili&#8230;</p>
                                    <p>Misa ya Tatu(Watoto)&#8230;</p>
                                    <a class="poststyle" href="2019/08/29/aliquam-placerat-tellus-ac-laoreet-euismod/index.html"></a>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="news-box last">
                                <div class="news-thumb">
                                    <a href="2019/04/23/interdum-et-malesuada-fames-ac-ante-ipsum-primis/index.html"><img src="wp-content/uploads/2019/08/gallery07.jpg" alt=" " /></a>
                                </div>
                                <div class="newsdesc">
                                    <div class="postdt"><span>{{date('Y')}}</span></div>
                                    <div class="PostMeta">

                                    </div>
                                    <h6><a href="2019/04/23/interdum-et-malesuada-fames-ac-ante-ipsum-primis/index.html">Ratiba za maungamo.</a></h6>
                                    <p>Misa ya kwanza&#8230;</p>
                                    <p>Misa ya Pili&#8230;</p>
                                    <p>Misa ya Tatu(Watoto)&#8230;</p>
                                    <a class="poststyle" href="2019/04/23/interdum-et-malesuada-fames-ac-ante-ipsum-primis/index.html"></a>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#303030; background-image:url(wp-content/themes/prayer-pro/images/donation_bg.jpg); background-repeat:no-repeat; background-position: center top; background-attachment:fixed; background-size:cover; " id="section7" class="menu_page">
            <div class="container">
                <div class="">
                    <div class="donation_left">
                        <h4>Malaki 3:10</h4>
                        <p>Leteni zaka kamili ghalani, ili kiwemo chakula katika nyumba yangu, mkanjaribu kwa njia hiyo,sema BWANA wa majeshi;mjue kama sitawafungulia madirisha ya mbinguni, na kuwamwagieni baraka, hata isiwepo nafasi ya kutosha,au la.</p>

                    </div>
                    <div class="donation_right">
                        <div class="funding-col">
                            <div class="funding-content">
                                <h3>0.00<span>Kilichobakia kusaidia</span></h3>
                                <div class="skill-bar-percent">0%</div>
                                <div class="causes-skill">
                                    <div class="skillbar-title"><span>Changia</span></div>
                                    <div class="skillbar " data-percent="1%" style="background:#d1d1d1;">
                                        <div class="skillbar-bar" style="background:#3490dc;"></div>
                                    </div>
                                </div>
                                <div class="cuase-raised"><span class="left">Kilichopatikana: 0.00</span><span class="right">Lengo: 0.00</span>
                                    <div class="clear"></div>
                                </div>
                                <a href="#" class="green_button" target="_blank">Changia</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <!-- <section style="background-color:#ffffff; " id="section8" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Video Gallery</h2>
                    <p>
                        <div class="vid_galle_left">
                            <div class="videobox hvr-glow">
                                <img src="wp-content/themes/prayer-pro/images/video-cover2.jpg" alt="" />
                                <a href="#" class="youtube-link" youtubeid="PPX08LvkQ5Y">
                                    <div class="playbtn hvr-shrink"></div>
                                </a>
                            </div>
                        </div>
                        <div class="vid_galle_right">
                            <div class="sec_content_main_title" style="text-align:left;">Birthday Party</div>Donec in metus lectus. Integer vulputate porta elit, fringilla mollis mag luctuInterdum et malesuada fames ac ante ipsum primis in fauci. Pellentesque in aliquam enim, quis lobortis arcu. Curabitur quiultrices
                            est malesuada fames ac ante ipsum primis in. Pellentesque in aliquam enim, quis lobortis malesuada fames ac ante ipsui fauci.</div><br />
                        <div class="clear"></div>
                    </p>
                    <div class="videogalley_wrapper">
                        <div class="most_video">
                            <div class="most_video_bg">
                                <div class="video-title-desc">
                                    <a href="#" class="youtube-link" youtubeid="HzTHDZh35Vs"><i class="fas fa-caret-right"></i></a>
                                </div>
                                <img src="wp-content/themes/prayer-pro/images/vg1.jpg" alt="" />
                            </div>
                        </div>
                        <div class="most_video">
                            <div class="most_video_bg">
                                <div class="video-title-desc">
                                    <a href="#" class="youtube-link" youtubeid="QyvavfIGyLs"><i class="fas fa-caret-right"></i></a>
                                </div>
                                <img src="wp-content/themes/prayer-pro/images/vg2.jpg" alt="" />
                            </div>
                        </div>
                        <div class="most_video">
                            <div class="most_video_bg">
                                <div class="video-title-desc">
                                    <a href="#" class="youtube-link" youtubeid="yFxxaa1Qt8U"><i class="fas fa-caret-right"></i></a>
                                </div>
                                <img src="wp-content/themes/prayer-pro/images/vg3.jpg" alt="" />
                            </div>
                        </div>
                        <div class="most_video">
                            <div class="most_video_bg">
                                <div class="video-title-desc">
                                    <a href="#" class="youtube-link" youtubeid="Ccr62sR8Pec"><i class="fas fa-caret-right"></i></a>
                                </div>
                                <img src="wp-content/themes/prayer-pro/images/vg4.jpg" alt="" />
                            </div>
                        </div>
                        <div class="most_video">
                            <div class="most_video_bg">
                                <div class="video-title-desc">
                                    <a href="#" class="youtube-link" youtubeid="60ItHLz5WEA"><i class="fas fa-caret-right"></i></a>
                                </div>
                                <img src="wp-content/themes/prayer-pro/images/vg5.jpg" alt="" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>

        </section> -->
        <section style="background-color:#f4f4f4; " id="section9" class="menu_page">
            <div class="container">
                <div class="">
                    <p>
                        <div class="pray2column_services">
                            <div class="prayimgbx"><img src="wp-content/themes/prayer-pro/images/prayimg_left.jpg"></div>
                            <div class="pray2column_desc">
                                <h4>Omba misa</h4>
                                <p>Mkristo anaweza kuomba misa!</p>
                                <a href="#" class="green_button">Omba Misa</a>
                            </div>
                        </div>
                        <div class="pray2column_services">
                            <div class="prayimgbx"><img src="wp-content/themes/prayer-pro/images/prayimg_right.jpg"></div>
                            <div class="pray2column_desc">
                                <h4>Kutoa shukrani</h4>
                                <p>Msaada wako wa kiuchumi unahitajika kueneza injili ya BWANA.</p>
                                <a href="#" class="green_button">Changia mtandaoni</a>
                            </div>
                        </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
        <section style="background-color:#ffffff; " id="section10" class="menu_page">
            <div class="container">
                <div class="">
                    <h2 class="section_title">Miradi Yetu</h2>
                    <div class="subtitle" style="font-size:16px; color:#535353; text-align:center;">Nunc commodo lacinia ipsum, scelerisque cursus libero ullamcorper sed. Praesent fermentum nisl ac neque tristique porttitor. Sed lectus lacus, vestibulum at fermentum.</div>
                    <p>
                        <div class="causesbx ">
                            <a href="#" target="_blank">
                                <div class="circleimgbox"><img src="wp-content/themes/prayer-pro/images/causes01.jpg" /></div>
                                <div class="titlebox">
                                    <!-- <h4>Helping People</h4> -->
                                    <p>Donec in metus lec vulputate porta elit, fringilla mollis</p>
                                </div>
                            </a>
                        </div>
                        <div class="causesbx ">
                            <a href="#" target="_blank">
                                <div class="circleimgbox"><img src="wp-content/themes/prayer-pro/images/causes02.jpg" /></div>
                                <div class="titlebox">
                                    <!-- <h4>Teach Childrens</h4> -->
                                    <p>Donec in metus lec vulputate porta elit, fringilla mollis</p>
                                </div>
                            </a>
                        </div>
                        <div class="causesbx ">
                            <a href="#" target="_blank">
                                <div class="circleimgbox"><img src="wp-content/themes/prayer-pro/images/causes03.jpg" /></div>
                                <div class="titlebox">
                                    <!-- <h4>Nothing Else Matters</h4> -->
                                    <p>Donec in metus lec vulputate porta elit, fringilla mollis</p>
                                </div>
                            </a>
                        </div>
                        <div class="causesbx last">
                            <a href="#" target="_blank">
                                <div class="circleimgbox"><img src="wp-content/themes/prayer-pro/images/causes04.jpg" /></div>
                                <div class="titlebox">
                                    <!-- <h4>Hope For US</h4> -->
                                    <p>Donec in metus lec vulputate porta elit, fringilla mollis</p>
                                </div>
                            </a>
                        </div>
                    </p>
                </div>
                <!-- .end section class -->
                <div class="clear"></div>
            </div>
            <!-- container -->
        </section>
@endsection