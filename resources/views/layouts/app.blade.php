<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if($church_details == "")
        <title>{{ config('app.name', 'Laravel') }}</title>
    @else
        <title>{{$church_details->centre_name}}</title>
    @endif

    <link rel="icon" href="{{asset('images/icon.png')}}" type="image/png">

    {{-- fontawesome --}}
    <link rel="stylesheet" href="{{asset('/css/all.min.css')}}">

    <link rel="stylesheet" href="{{asset('/css/nucleo.css')}}">

    {{-- dashboard bootstrap --}}
    <link rel="stylesheet" href="{{asset('/css/argon_bootstrap.css')}}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <style type="text/css">
        @font-face {
            font-family: 'crimsonpro', serif;
            src: url("{{url('/fonts/crimsonpro.woff2')}}");
        }
    </style>

</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
            @include('sweetalert::alert')
        </main>
    </div>
</body>
</html>
