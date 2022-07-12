<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaraDESK')</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="LaraDESK - Take ticketing to another level">
    <meta name="description" content="LaraDESK : your next level ticketing tool to go Agile without headache ">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://34.140.17.43/">
    <meta property="og:title" content="LaraDESK - Take ticketing to another level">
    <meta property="og:description" content="LaraDESK : your next level ticketing tool to go Agile without headache ">
    <meta property="og:image"
        content="{{asset("images/meta.png")}}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://34.140.17.43/">
    <meta property="twitter:title" content="LaraDESK - Take ticketing to another level">
    <meta property="twitter:description"
        content="LaraDESK : your next level ticketing tool to go Agile without headache ">
    <meta property="twitter:image"
        content="{{asset("images/meta.png")}}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

</head>

<body class="font-sans antialiased">
    @include('layouts.navigation')

    <!-- Page Content -->
    <div class="app-container">
        @yield('content')
    </div>
</body>

</html>
