<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{asset('/assets/fingerlogo.png')}}" rel='icon' type='image/x-icon'/>
        <title>@yield('title', 'ZP Scanner App')</title>

        <!-- CSS & jQuery -->
        <link href="{{asset('/css/style.css')}}" rel="stylesheet"/>
        <script src="{{asset('/js/jquery.js')}}"></script>
    </head>
    <body>
        <!-- App Container -->
        <div class="app-container">
            {{ $slot }}
        </div>
        <!-- Script -->
        <script src="{{asset('/js/script.js')}}"></script>
        <!-- Phosphor Icons -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
    </body>
</html>