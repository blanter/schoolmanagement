<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/img/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>@yield('title', 'School Management App')</title>

    <!-- CSS & jQuery -->
    <link href="{{asset('/css/style.css')}}?ver=10" rel="stylesheet" />
    <script src="{{asset('/js/jquery.js')}}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- App Container -->
    <div class="app-container">
        {{ $slot }}
    </div>
    <form action="{{ route('logout') }}" method="POST" class="logout-button">
        @csrf
        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000"
                viewBox="0 0 256 256">
                <path
                    d="M120,216a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h64a8,8,0,0,1,0,16H56V208h56A8,8,0,0,1,120,216Zm109.66-93.66-40-40a8,8,0,0,0-11.32,11.32L204.69,120H112a8,8,0,0,0,0,16h92.69l-26.35,26.34a8,8,0,0,0,11.32,11.32l40-40A8,8,0,0,0,229.66,122.34Z">
                </path>
            </svg></button>
    </form>
    <!-- Script -->
    <script src="{{asset('/js/script.js')}}?ver=3"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</body>

</html>