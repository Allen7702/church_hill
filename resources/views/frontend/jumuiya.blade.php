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

            <h2 class="animate__animated animate__fadeInDown text-black-50" align="center">Jumuiya</h2>
            <p class="animate__animated animate__fadeInUp text-black-50  w-100" align="center">Karibu katika wavuti ya kanisa, usisite kuangalia jumuiya mbalimbali.</p>
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
</section><!-- End Hero -->

<main id="main">

    <!-- ======= Featured Section ======= -->
    <section id="featured" class="featured">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Jumuiya Zetu</h2>
            </div>

            @if($data_jumuiya->isNotEmpty())


                <div class="row">
                    @foreach ($data_jumuiya as $row)
                    <div class="col-lg-4 mb-3">
                        <div class="icon-box">
                            <h3><a href="">{{$row->jina_la_jumuiya}}</a></h3><hr>
                            <p>{{$row->comment}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            @endif

        </div>
    </section><!-- End Featured Section -->
</main>
@endsection
