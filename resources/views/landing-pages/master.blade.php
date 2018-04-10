<!doctype html>
<html lang="lang="{{ config('app.locale') }}"">
<head>
    <meta charset="utf-8" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BookNow</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />

    <!--         Core CSS          -->
    <link href="{{ asset('bsbmd/css/landing.css') }}" rel="stylesheet">
    <link href="{{ asset('bsbmd/css/material-kit.css') }}" rel="stylesheet">

    <!--     Fonts and icons     -->
    <link href="{{ asset('bsbmd/css/landing-fonts.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>

    @stack('styles')
</head>

<body>

@yield('content')

</body>

<!--   Core JS Files   -->
<script src="{{ asset('bsbmd/js/landing.js') }}"></script>
<script src="{{ asset('bsbmd/js/material.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('bsbmd/js/material-kit.js') }}" type="text/javascript"></script>
@stack('scripts')
</html>
