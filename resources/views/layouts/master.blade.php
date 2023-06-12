<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> @include('meta::manager')
    <meta name="csrf-token" content="{{ csrf_token() }}"> @if(empty($church_details))
    <title>{{ config('app.name', 'Laravel') }}</title>
    @else
    <title>{{$church_details->centre_name}}</title>
    @endif
    <link rel="icon" href="{{asset('images/icon.png')}}" type="image/png"> {{-- scripts --}} {{-- jquery files --}}

    <script src="{{asset('/js/jquery.3.2.1.min.js')}}"></script>

    {{--
    <script src="{{asset('/js/jquery-3.6.0.min.js')}}"></script> --}}

    <script src="{{asset('/js/highcharts.js')}}"></script>

    <!-- Sweet Alert 2-->
    <script src="{{asset('/js/sweetalert2.all.min.js')}}"></script>

    {{-- datatable --}} {{--
    <script src="{{asset('/js/datatables.bootstrap4.min.js')}}"></script> --}}

    <script type="text/javascript" src="{{asset('js/datatables_new.min.js')}}"></script>

    {{-- popper --}}
    <script src="{{asset('/js/popper.min.js')}}"></script>

    <script src="{{asset('/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('css/datatables.min.css')}}" /> {{-- sweetalert 2 --}}
    <link rel="stylesheet" href="{{asset('/css/sweetalert2.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="{{asset('/css/bootstrap-datepicker.min.css')}}" />
    <script src="{{asset('/js/webfont.min.js')}}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{asset('/css/fonts.min.css')}}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <style type="text/css">
        @font-face {
            font-family: 'crimsonpro', serif;
            src: url("{{url('/fonts/crimsonpro.woff2')}}");
        }
    </style>

    <!-- ==================== USED FOR TOASTING IN PAGES =============-->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/atlantis.css')}}">
    <link rel="stylesheet" href="{{asset('/css/my_style.css')}}">
</head>

<body>
    <div class="wrapper">

        <div class="main-header">

            {{-- logo section --}}
            <div class="logo-header" data-background-color="white">

                @if(!(empty($church_details)))
                <a href="{{url('home')}}" class="logo">
						<img src="{{asset('/uploads/images/'.$church_details->photo)}}" height="50px" width="50px" alt="logo" class="navbar-brand rounded-circle">
					</a> @else
                <a href="{{url('home')}}" class="logo">
                    <img src="{{asset('/images/atlais.png')}}" width="120px" alt="logo" class="navbar-brand ">
                </a>
                @endif

                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>

                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>

                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
                </div>

            </div>

            {{-- navbar header --}}
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="white">

                <div class="container-fluid">
                    <div class="collapse" id="search-nav">
                        <form class="navbar-left navbar-form nav-search mr-md-3" action="/tafuta" role="search">

                            @csrf

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pr-1">
										<i class="fas fa-search search-icon"></i>
									</button>
                                </div>
                                <input type="search" placeholder="Tafuta..." class="form-control" name="q" style="background: #FFFFFF;">
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item toggle-nav-search hidden-caret">
                            <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                                <i class="fas fa-search"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link" href="{{url('home')}}" role="button">
                                <i class="fa fa-home text-info"></i>
                            </a>

                            <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope"></i>
                            </a>
                            <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                                <li>
                                    <div class="dropdown-title d-flex justify-content-between align-items-center">
                                        Tuma ujumbe
                                    </div>
                                </li>

                                <li>
                                    <a class="see-all" href="{{route('messages.invoice')}}">Malipo meseji<i class="fa fa-angle-double-right"></i> </a>
                                </li>

                                <li>
                                    <a class="see-all" href="{{url('meseji_shukrani_ukumbusho')}}">Shukrani & Ukumbusho<i class="fa fa-angle-double-right"></i> </a>
                                </li>

                                <li>
                                    <a class="see-all" href="{{url('ukurasa_meseji')}}">Nyinginezo<i class="fa fa-angle-double-right"></i> </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="notification">0</span>
                            </a>

                            <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                                {{--
                                <li>
                                    <div class="dropdown-title">You have 4 new notification</div>
                                </li>
                                <li>
                                    <div class="notif-scroll scrollbar-outer">
                                        <div class="notif-center">
                                            <a href="#">
                                                <div class="notif-icon notif-primary"> <i class="fa fa-user-plus"></i> </div>
                                                <div class="notif-content">
                                                    <span class="block">
														New user registered
													</span>
                                                    <span class="time">5 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-icon notif-success"> <i class="fa fa-comment"></i> </div>
                                                <div class="notif-content">
                                                    <span class="block">
														Rahmad commented on Admin
													</span>
                                                    <span class="time">12 minutes ago</span>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
                                </li> --}}
                            </ul>
                        </li>

                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fas fa-layer-group"></i>
                            </a>
                            <div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
                                <div class="quick-actions-header">
                                    <span class="title mb-1">Kiungo cha haraka</span> {{-- <span class="subtitle op-8">Shortcuts</span> --}}
                                </div>
                                <div class="quick-actions-scroll scrollbar-outer">
                                    <div class="quick-actions-items">
                                        <div class="row m-0">
                                          
                                            <a class="col-6 col-md-4 p-0" href="{{url('michango_taslimu_mkupuo')}}">
                                                <div class="quick-actions-item">
                                                    <i class="flaticon-graph"></i>
                                                    <span class="text">Michango mkupuo</span>
                                                    </div>
                                            </a>
                                        
                                            <a class="col-6 col-md-4 p-0" href="{{url('zaka_mkupuo')}}">
                                                <div class="quick-actions-item">
                                                    <i class="flaticon-coins"></i>
                                                    <span class="text">Zaka mkupuo</span>
                                                </div>
                                            </a>
                                          
                                            <a class="col-6 col-md-4 p-0" href="{{url('stakabadhi')}}">
                                                <div class="quick-actions-item">
                                                    <i class="flaticon-file-1"></i>
                                                    <span class="text">Stakabadhi za malipo</span>
                                                </div>
                                            </a>
                                           
                                            <a class="col-6 col-md-4 p-0" href="{{route('activity.index')}}">
                                                <div class="quick-actions-item">
                                                    <i class="flaticon-archive"></i>
                                                    <span class="text">Matukio Katika Mfumo</span>
                                                </div>
                                            </a>
                                          

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </li>

                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    @if(auth()->user()->picha == "profile.png")
                                    <img src="{{asset('/images/profile.png')}}" alt="photo" class="avatar-img rounded-circle"> @else
                                    <img src="{{url('/uploads/images/'.auth()->user()->picha)}}" alt="picha" class="avatar-img rounded-circle"> @endif
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                @if(auth()->user()->picha == "")
                                                <img src="{{asset('/images/profile.png')}}" alt="profile" class="avatar-img rounded"> @else
                                                <img src="{{url('/uploads/images/'.auth()->user()->picha)}}" alt="profile" class="avatar-img rounded"> @endif
                                            </div>
                                            <div class="u-text">
                                                <h4>{{auth()->user()->name}}</h4>
                                                <p class="text-muted">{{auth()->user()->email}}</p><a href="{{url('users_profile')}}" class="btn btn-xs btn-info btn-sm">Taarifa zako</a>
                                            </div>
                                        </div>
                                    </li>
                                   
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('activity.zaka') }}">Kumbukumbu za shughuli (zaka)</a>
                                    </li>
                                   
                                    <li>
                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
											document.getElementById('logout-form').submit();">
                                            <i class="fas fa-fw fa-power-off"></i> &nbsp;&nbsp; {{ __('Ondoka') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                    </div>
            </nav>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-wrapper scrollbar scrollbar-inner">
                    <div class="sidebar-content">

                        <ul class="nav nav-primary">
                            <li class="nav-item {{ Request::is('kanda*') || Request::is('kikundi*') || Request::is('home*') || Request::is('jumuiya*') || Request::is('familia*') || Request::is('wanafamilia*') || Request::is('mwanafamilia*') ? 'active' : '' }}">
                                <a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                                    <i class="fas fa-fw fa-cross"></i>
                                    <p>Parokia </p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="dashboard">
                                    <ul class="nav nav-collapse">
                                      
                                        <li>
                                            <a href="{{url('kanda')}}">
                                                <span class="sub-item">{{$sahihisha_kanda ? $sahihisha_kanda->name : 'Kanda'}}</span>
                                            </a>
                                        </li>
                                    
                                        <li>
                                            <a href="{{url('jumuiya')}}">
                                                <span class="sub-item">Jumuiya</span>
                                            </a>
                                        </li>
                                       
                                        <li>
                                            <a href="{{url('kikundi')}}">
                                                <span class="sub-item">Vyama vya kitume</span>
                                            </a>
                                        </li>
                                      
                                        <li>
                                            <a href="{{url('familia')}}">
                                                <span class="sub-item">Familia</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                          
                            <li class="nav-item {{Request::is('masakramenti_jumla*') || Request::is('masakramenti_kanda*') || Request::is('masakramenti_kanda_husika*') || Request::is('masakramenti_jumuiya*') ?'active':''}}">
                                <a data-toggle="collapse" href="#masakramenti">
                                    <i class="fas fa-fw fa-chart-pie"></i>
                                    <p>Masakramenti</p>
                                    <span class="caret"></span>
                                </a>
                               
                                <div class="collapse" id="masakramenti">
                                    <ul class="nav nav-collapse {{ Request::is('masakramenti_kanda')? 'collapse':''}}">
                                        
                                        <li>
                                            <a href="{{url('masakramenti_jumla')}}">
                                                <span class="sub-item">Kijumla</span>
                                            </a>
                                        </li>
                                      
                                        <li>
                                            <a href="{{url('masakramenti_kanda')}}">
                                                <span class="sub-item">{{$sahihisha_kanda ? $sahihisha_kanda->name : 'Kanda'}}</span>
                                            </a>
                                        </li>
                                      
                                        <li>
                                            <a href="{{url('masakramenti_jumuiya')}}">
                                                <span class="sub-item">Kijumuiya</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                           
                            <li class="nav-item {{Request::is('mafundisho_enrollments*')?'active':''}}">
                                <a data-toggle="collapse" href="#mafundisho">
                                    <i class="fas fa-fw fa-book"></i>
                                    <p>Mafundisho</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="mafundisho">
                                    <ul class="nav nav-collapse {{ Request::is('mafundisho_enrollments')? 'collapse':''}}">

                                        <li>
                                            <a href="{{route('mafundisho_enrollments.takwimu', ['type' => 'komunio'])}}">
                                                <span class="sub-item">Komunio</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{route('mafundisho_enrollments.takwimu', ['type' => 'kipaimara'])}}">
                                                <span class="sub-item">Kipaimara</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{route('mafundisho_enrollments.takwimu', [ 'type' => 'ndoa'])}}">
                                                <span class="sub-item">Ndoa</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                          
                            <li class="nav-item
						{{ Request::is('sadaka_kuu_takwimu*') || Request::is('matumizi_zaidi*') || Request::is('mapato_zaidi*') || Request::is('matumizi_taslimu*') || Request::is('matumizi_benki*')
						|| Request::is('mapato_matumizi_takwimu*') || Request::is('michango_taslimu_miezi*') || Request::is('matumizi_taslimu_zaidi*')  || Request::is('aina_za_michango*') || Request::is('michango_benki*')
						|| Request::is('michango_benki_miezi*') || Request::is('michango_taslimu*') || Request::is('michango_takwimu*') || Request::is('zaka_takwimu*') || Request::is('zaka_mkupuo*')
						|| Request::is('sadaka_kuu*') || Request::is('bajeti_makisio*') || Request::is('zaka*') || Request::is('sadaka_jumuiya*') || Request::is('sadaka_kuu_zaidi*') || Request::is('sadaka_jumuiya_zaidi*') ?'active':''}}">

                                <a data-toggle="collapse" href="#fedha">
                                    <i class="fas fa-fw fa-briefcase"></i>
                                    <p>Fedha</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="fedha">
                                    <ul class="nav nav-collapse">
                                       
                                        <li>
                                            <a href="{{route('sadaka_za_misas.takwimu')}}">
                                                <span class="sub-item">Sadaka Za Misa</span>
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="{{url('sadaka_kuu_takwimu')}}">
                                                <span class="sub-item">Sadaka ya jumuiya</span>
                                            </a>
                                        </li>
                                       
                                        <li>
                                            <a href="{{url('zaka_takwimu')}}">
                                                <span class="sub-item">Zaka</span>
                                            </a>
                                        </li>
                                      
                                        <li>
                                            <a href="{{url('michango_takwimu')}}">
                                                <span class="sub-item">Michango</span>
                                            </a>
                                        </li>
                                 
                                        <li>
                                            <a href="{{route('waliotoa_wasiotoa',['mchango'=>'zaka','jumuiya'=>'all','wachangiaji'=>'all'])}}">
                                                <span class="sub-item">Waliotoa/Wasiotoa</span>
                                            </a>
                                        </li>
                                       
                                        <li>
                                            <a href="{{url('mapato_matumizi_takwimu')}}">
                                                <span class="sub-item">Mapato/Matumizi</span>
                                            </a>
                                        </li>
                                      
                                        <li>
                                            <a href="{{url('bajeti_makisio')}}">
                                                <span class="sub-item">Makisio ya bajeti</span>
                                            </a>
                                        </li>
                                  
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item {{ Request::is('masakramenti_ripoti*') || Request::is('fedha_ripoti*') ?'active':''}}">
                                <a data-toggle="collapse" href="#ripoti">
                                    <i class="fas fa-fw fa-file-alt"></i>
                                    <p>Ripoti</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="ripoti">
                                    <ul class="nav nav-collapse">
                                    
                                        <li>
                                            <a href="{{url('masakramenti_ripoti')}}">
                                                <span class="sub-item">Masakramenti</span>
                                            </a>
                                        </li>
                                    
                                        <li>
                                            <a href="{{url('fedha_ripoti')}}">
                                                <span class="sub-item">Fedha</span>
                                            </a>
                                        </li>
                                     
                                        <li>
                                            <a href="">
                                                <span class="sub-item">Zinginezo</span>
                                            </a>
                                        </li>
                                   
                                    </ul>
                                </div>
                            </li>
                        
                            <li class="nav-item {{ Request::is('users*') || Request::is('vyeo_kanisa*') || Request::is('viongozi_zaidi*') ? 'active':''}}">
                                <a data-toggle="collapse" href="#viongozi">
                                    <i class="fas fa-fw fa-users"></i>
                                    <p>Viongozi</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="viongozi">
                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{url('users')}}">
                                                <span class="sub-item">Viongozi</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('vyeo_kanisa')}}">
                                                <span class="sub-item">Vyeo</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                        
                            <li class="nav-item {{ Request::is('akaunti_za_benki') || Request::is('watoa_huduma') || Request::is('aina_za_misa') || Request::is('aina_za_huduma') || Request::is('mali_za_kanisa') || Request::is('aina_za_mali') || Request::is('makundi_rika') ?'active':''}}">

                                <a data-toggle="collapse" href="#mengineyo">
                                    <i class="fas fa-fw fa-th-list"></i>
                                    <p>Mengineyo</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="mengineyo">
                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{url('aina_za_misa')}}">
                                                <span class="sub-item">Aina za misa</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('aina_za_sadaka')}}">
                                                <span class="sub-item">Aina za sadaka</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{route('ratiba-ya-misa.index')}}">
                                                <span class="sub-item">Ratiba ya Misa</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('akaunti_za_benki')}}">
                                                <span class="sub-item">Akaunti za benki</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('watoa_huduma')}}">
                                                <span class="sub-item">Watoa huduma/Huduma</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('mali_za_kanisa')}}">
                                                <span class="sub-item">Mali za kanisa</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('makundi_rika')}}">
                                                <span class="sub-item">Makundi ya umri (Rika)</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                          
                            <li class="nav-item {{ Request::is('matukio') || Request::is('historia') || Request::is('matangazo') ?'active':''}}">

                                <a data-toggle="collapse" href="#tovuti">
                                    <i class="fas fa-fw fa-globe"></i>
                                    <p>Tovuti</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="tovuti">
                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{url('matukio')}}">
                                                <span class="sub-item">Matukio</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('historia')}}">
                                                <span class="sub-item">Historia</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{url('matangazo')}}">
                                                <span class="sub-item">Matangazo</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                           
                            <li class="nav-item has-treeview {{ Request::is('centre_details') || Request::is('usaidizi*') || Request::is('orodha_majina*') || Request::is('orodha_familia*') || Request::is('orodha_wanafamilia*') || Request::is('nyaraka')? 'active':'' }}">

                                <a data-toggle="collapse" href="#mfumo">
                                    <i class="fas fa-fw fa-cogs"></i>
                                    <p>Mfumo</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="mfumo">
                                    <ul class="nav nav-treeview nav-collapse">
                                        <li>
                                            <a href="{{url('centre_details')}}">
                                                <span class="sub-item">Taarifa za parokia</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('usaidizi')}}">
                                                <span class="sub-item">Usaidizi</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('nyaraka')}}">
                                                <span class="sub-item">Nyaraka muhimu</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('sahihisha.index')}}">
                                                <span class="sub-item">Sahihisha mfumo</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                    
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Sidebar -->

            <div class="main-panel">
                <div class="content">
                    <div class="page-inner">
                        @yield('content') @include('sweetalert::alert')
                    </div>
                </div>
            </div>

        </div>

        <!-- Fonts and icons -->
        <script src="{{asset('/js/webfont.min.js')}}"></script>

        <!-- Bootstrap Notify -->
        <script src="{{asset('/js/bootstrap-notify.min.js')}}"></script>

        <!-- jQuery UI -->
        <script src="{{asset('/js/jquery-ui.min.js')}}"></script>
        <script src="{{asset('/js/jquery.ui.touch-punch.min.js')}}"></script>

        <!-- jQuery Scrollbar -->
        <script src="{{asset('/js/jquery.scrollbar.min.js')}}"></script>

        <script src="{{asset('/js/select2.full.min.js')}}"></script>

        <!-- Atlantis JS -->
        <script src="{{asset('/js/atlantis.min.js')}}"></script>

        <script src="{{asset('/js/bootstrap-datepicker.min.js')}}">
        </script>


</body>

</html>