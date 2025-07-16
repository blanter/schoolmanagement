<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{asset('/assets/logo.png')}}" rel='icon' type='image/x-icon'/>

        <title>@yield('title', 'Lifebook App')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('/build/assets/app-vE7QAWX2.css') }}">
        <script type="module" src="{{ asset('/build/assets/app-Bf4POITK.js') }}"></script>
        <link href="{{asset('/css/welcome-style.css')}}" rel="stylesheet"/>
    </head>
    <body>
        <div class="app-container app-login-box">
            <div class="app-my-box">
                <!-- LOGO HEADER -->
                <div class="custom-login-header">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>
                <!-- LOGIN BOX -->
                <div class="custom-login-box w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
