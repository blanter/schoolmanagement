<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/img/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>@yield('title', 'School Management App')</title>
    <link href="{{asset('/css/welcome-style.css')}}?ver=2" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    {{ $slot }}
</body>

</html>