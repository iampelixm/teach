<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @stack('meta')
    @section('meta')
    @show
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$page_title ?? 'Портал обучения'}}</title>
    <!-- Styles -->
    @stack('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @section('header')
    @show
    @section('nav')
        <div class="container">
        @component('component.navbar', ['items'=>($nav ?? [])])
        @endcomponent
        </div>
    @show
    @section('hero')
    @show
    @section('content')
    @show
    @section('footer')
    @show
   <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('javascript')
</body>
</html>