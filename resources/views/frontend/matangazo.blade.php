@extends('layouts.front_master')
@section('content')
<section id="hero">
    <div class="hero-container d-flex flex-row">
        <div class="w-50 py-9 px-3">
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;
            <br/>&nbsp;

            <h2 class="animate__animated animate__fadeInDown text-black-50" align="center">Matangazo</h2>
            <p class="animate__animated animate__fadeInUp text-black-50  w-100" align="center">Karibu katika wavuti ya kanisa, usisite kusoma matangazo mbalimbali.</p>
        </div>
    <div id="heroCarousel" class="carousel slide carousel-fade  w-50" data-ride="carousel">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

            @if($matukios->isEmpty())

            <div class="carousel-item active" style="background: url({{('/uploads/church.jpg')}});">
                <div class="carousel-container">
                    <div class="carousel-content">
                        <h2 class="animate__animated animate__fadeInDown">Wavuti ya kanisa</h2>
                        <p class="animate__animated animate__fadeInUp">Karibu katika wavuti ya kanisa, usisite kusoma matangazo mbalimbali..</p>
                    </div>
                </div>
            </div>

            @else

                @foreach ($matukios as $item)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}" style="background-image: url({{('/uploads/images/'.$item->picha)}});">
                        <div class="carousel-container">
                        <div class="carousel-content">
                            <h2 class="animate__animated animate__fadeInDown">{{$item->kichwa}}</h2>
                            <p class="animate__animated animate__fadeInUp">{{$item->maelezo}}</p>
                        </div>
                        </div>
                    </div>
                @endforeach

            @endif

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon icofont-rounded-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon icofont-rounded-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
        </a>

    </div>
    </div>
</section>

<main id="main">

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog" style="margin-top: 10px;">
    <div class="container">

        <div class="section-title" data-aos="fade-up">
            <h2>Matangazo yetu</h2>
        </div>

        <div class="row">

        <div class="col-md-12 entries">

            @if($data_matangazo->isNotEmpty())

            @foreach ($data_matangazo as $item)
            <article class="entry">

                <h2 class="entry-title">
                    <a href="{{url('matangazo_yetu')}}">{{$item->kichwa}}</a><hr>
                </h2>

                <div class="entry-meta">
                    <ul>
                        <li class="d-flex align-items-center"><i class="icofont-wall-clock"></i> <a href="{{url('historia_leo')}}"><time datetime="2020-01-01">{{Carbon::parse($item->tarehe)->format('M d, Y')}}</time></a></li>
                    </ul>
                    </div>

                    <div class="entry-content">
                    <p>
                        {{$item->maelezo}}
                    </p>

                    @if($item->attachment != "NULL")
                    <div class="read-more">
                        <a href="{{url('uploads/images/'.$item->attachment)}}">Soma zaidi</a>
                    </div>
                    @endif

                    </div>



                </article>
            @endforeach

            @else
            <article class="entry">

                <h2 class="entry-title">
                <a href="{{url('matangazo_yetu')}}">Ukurasa wa matangazo</a>
                </h2>

                <div class="entry-meta">
                <ul>
                    <li class="d-flex align-items-center"><i class="icofont-wall-clock"></i> <a href="{{url('historia_leo')}}"><time datetime="2020-01-01">{{Carbon::now()->format('M d, Y')}}</time></a></li>
                </ul>
                </div>

                <div class="entry-content">
                <p>
                    Karibu katika ukurasa wa matangazo mbalimbali kuhusiana na kanisa, ukurasa huu utahusisha matangazo mbalimbali yanayohusiana na kanisa letu..
                </p>

                </div>

            </article>
            @endif
        </div>

        </div>

    </div>
    </section>

</main>

@endsection
