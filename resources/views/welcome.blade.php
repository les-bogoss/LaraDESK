<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>welcome</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

</head>

<body>
    <div class="welcome-layout">
        <h1 class="welcome-title"><span class="welcome-title-light">Lara</span>DESK</h1>
        <p class="welcome-text">LaraDESK is a simple, easy to use, and powerful tool for managing your Laravel
            application.</p>
        <div class="welcome-buttons">
            <a href="{{ route('register') }}" id="welcome-button-register">register</a>
            <a href="{{ route('login') }}" id="welcome-button-login">login</a>
        </div>
    </div>
</body>

</html>
