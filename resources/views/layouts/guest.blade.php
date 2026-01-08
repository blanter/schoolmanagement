<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'School Management - Lifebook Academy')</title>
    <meta name="description"
        content="@yield('meta_description', 'School Management Application for Lifebook Academy teachers and staff.')">
    <meta name="keywords" content="@yield('meta_keywords', 'school management, lifebook academy, teacher portal')">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'School Management - Lifebook Academy')">
    <meta property="og:description"
        content="@yield('meta_description', 'School Management Application for Lifebook Academy teachers and staff.')">
    <meta property="og:image" content="{{ asset('/img/lifebookicon.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="@yield('title', 'School Management - Lifebook Academy')">
    <meta property="twitter:description"
        content="@yield('meta_description', 'School Management Application for Lifebook Academy teachers and staff.')">
    <meta property="twitter:image" content="{{ asset('/img/lifebookicon.png') }}">

    <link href="{{asset('/img/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <link href="{{asset('/css/welcome-style.css')}}?ver=3" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    {{ $slot }}
</body>

</html>