<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

    <!-- Bootstrap Core Css -->
    @section('css')
        {{ Html::style('bsbmd/plugins/bootstrap/css/bootstrap.css') }}
        {{ Html::style('bsbmd/plugins/node-waves/waves.css') }}
        {{ Html::style('bsbmd/plugins/animate-css/animate.css') }}
        {{ Html::style('bsbmd/plugins/morrisjs/morris.css') }}
        {{ Html::style('bsbmd/css/style.css') }}
        {{ Html::style('bsbmd/css/themes/all-themes.css') }}
        {{ Html::style('css/toastr.min.css')}}
        {{ Html::style('css/custom.css')}}
        {{ Html::style('css/lightbox.css')}}

         <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    @show

    @yield('extra-css')
</head>

<body class="theme-green">
    @include('layouts.partials.loader')
    <div class="overlay"></div>
    @include('layouts.partials.header')
    @include('layouts.partials.sidebar')

    <section class="content">
        @yield('content')
    </section>

    @section('script')
        {{Html::script('bsbmd/plugins/jquery/jquery.min.js')}}
        {{Html::script('bsbmd/plugins/bootstrap/js/bootstrap.js')}}
        {{-- {{Html::script('bsbmd/plugins/bootstrap-select/js/bootstrap-select.js')}} --}}
        {{Html::script('bsbmd/plugins/jquery-slimscroll/jquery.slimscroll.js')}}
        {{Html::script('bsbmd/plugins/node-waves/waves.js')}}
        {{Html::script('js/toastr.min.js')}}
        {{Html::script('js/lightbox.js')}}
    @show    
    @yield('extra-script')
    @include('message.message')
    @section('script-bottom')
        {{Html::script('bsbmd/js/admin.js')}}
        {{Html::script('bsbmd/js/demo.js')}}
        <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fitImagesInViewport': true
            });
        </script>
        @if(auth()->user() && auth()->user()->restaurants->count() > 0)
            <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
            <script>
                var pusher = new Pusher('432b739595525c6f571e', {
                cluster: 'ap1',
                encrypted: true
                });
            </script>
            @foreach(auth()->user()->restaurants as $restaurant)
                <script>
                    // Event for reservations
                    var channel = pusher.subscribe('reservation.pending.restaurant.' + '{{$restaurant->id}}');
                    channel.bind('App\\Events\\PendingReservationCreated', function(data) {
                        console.log(data.reservation);
                        var reservationsWrapper   = $('#dropdown-reservations-' + '{{$restaurant->fb_page_id}}');
                        if (reservationsWrapper) {
                            var reservationsToggle    = reservationsWrapper.find('a[data-toggle]');
                            var reservationsCountElem = reservationsToggle.find('span[data-count]');
                            var reservationsCount     = parseInt(reservationsCountElem.data('count'));
                            var reservations          = reservationsWrapper.find('ul#dropdown-menu-reservations');
                            var existingReservations = reservations.html();
                            var newNotificationHtml = `
                                <li>
                                    <a href="javascript:void(0);" class=" waves-effect waves-block">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">event_available</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>` + `New table booking order` + `</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 14 mins ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            `;
                            reservations.html(newNotificationHtml + existingReservations);
                            reservationsCount += 1;
                            reservationsCountElem.attr('data-count', reservationsCount);
                            reservationsCountElem.text(reservationsCount);
                        }
                        toastr.options.timeOut = 6000; // How long the toast will display without user interaction
                        toastr.options.extendedTimeOut = 8000; // How long the toast will display after a user hovers over it
                        toastr.success('<a href="{{route('reservation.index', $restaurant->slug)}}">New reservation order for {{$restaurant->name}}</a>');
                    });
                </script>
            @endforeach
        @endif
    @show
</body>

</html>