<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Bootstrap Core Css -->
    @section('css')
        {{ Html::style('bsbmd/plugins/bootstrap/css/bootstrap.css') }}
        {{ Html::style('bsbmd/plugins/node-waves/waves.css') }}
        {{ Html::style('bsbmd/plugins/animate-css/animate.css') }}
        {{-- {{ Html::style('bsbmd/plugins/morrisjs/morris.css') }} --}}
        {{ Html::style('bsbmd/css/style.css') }}
        {{ Html::style('bsbmd/css/themes/all-themes.css') }}
        {{ Html::style('bsbmd/css/restaurant.css') }}
        {{ Html::style('bsbmd/css/landing-fonts.css') }}
        {{ Html::style('css/toastr.min.css')}}
        {{ Html::style('css/custom.css')}}
         <!-- Google Fonts -->
        }
        }
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    @show

    @yield('extra-css')
</head>

<body class="theme-green">
{{--     @include('restaurant.partials.loader') --}}
    <div class="overlay"></div>
    @include('restaurant.partials.header')
    @include('restaurant.partials.right-sidebar')

    <section class="content">
        @yield('content')
    </section>

    @section('script')
        {{Html::script('bsbmd/plugins/jquery/jquery.min.js')}}
        {{Html::script('bsbmd/plugins/bootstrap/js/bootstrap.js')}}
        {{Html::script('bsbmd/plugins/bootstrap-select/js/bootstrap-select.js')}}
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
                    var channel = pusher.subscribe('reservation.restaurant.' + '{{$restaurant->id}}');
                    channel.bind('App\\Events\\ReservationUpdated', function(data) {
                        console.log(data.reservation);
                        var reservationsWrapper   = $('#dropdown-reservations-' + data.reservation.restaurant.fb_page_id);
                        if (reservationsWrapper.length) {
                            var reservationsToggle    = reservationsWrapper.find('a[data-toggle]');
                            var reservationsCountElem = reservationsToggle.find('span[data-count]');
                            var reservationsCount; // parseInt(reservationsCountElem.data('count'));
                            var reservations          = reservationsWrapper.find('ul#dropdown-menu-reservations');
                            var existingReservations = reservations.html();
                            if (data.reservation.status === 'pending') {
                                // insert new to notifications list
                                var notification_item = reservations.find('li#reservation-' + data.reservation.id);
                                if (notification_item.length) {
                                    toastr.options.timeOut = 6000; // How long the toast will display without user interaction
                                    toastr.options.extendedTimeOut = 8000; // How long the toast will display after a user hovers over it
                                    toastr.success('<a href="' + data.link + '">New reservation order for ' + data.reservation.restaurant.name + '</a>');
                                } else {
                                    var newNotificationHtml;
                                    if (data.reservation.created_by_bot == 1) {
                                        newNotificationHtml = `
                                        <li id="reservation-` + data.reservation.id + `">
                                            <a href="` + data.link + `" class=" waves-effect waves-block">
                                                <div class="icon-circle bg-light-green">
                                                    <img src="{{asset('bot-icon.png')}}" width="36" height="36" alt="Bot" style="border-radius: 50%;">
                                                </div>
                                                <div class="menu-info">
                                                    <h4>` + `New table booking order` + `</h4>
                                                    <p>
                                                        <i class="material-icons">access_time</i>` + data.reservation.updated_at + `
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        `;
                                    } else {
                                        newNotificationHtml = `
                                        <li id="reservation-` + data.reservation.id + `">
                                            <a href="` + data.link + `" class=" waves-effect waves-block">
                                                <div class="icon-circle bg-light-green">
                                                    <img src="` + data.last_editor.avatar + `" width="36" height="36" alt="` +data.last_editor.name + `" style="border-radius: 50%;">
                                                </div>
                                                <div class="menu-info">
                                                    <h4>` + `New table booking order` + `</h4>
                                                    <p>
                                                        <i class="material-icons">access_time</i>` + data.reservation.updated_at + `
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        `;
                                    }
                                    // reservationsCountElem = reservationsToggle.find('span[data-count]');
                                    
                                    reservations.html(newNotificationHtml + existingReservations);
                                    reservationsCount     = parseInt(reservationsCountElem.attr('data-count'));
                                    reservationsCount += 1;
                                    reservationsCountElem.attr('data-count', reservationsCount);
                                    reservationsCountElem.text(reservationsCount);
                                    toastr.options.timeOut = 6000; // How long the toast will display without user interaction
                                    toastr.options.extendedTimeOut = 8000; // How long the toast will display after a user hovers over it
                                    toastr.success('<a href="' + data.link + '">New reservation order for ' + data.reservation.restaurant.name + '</a>');
                                }
                            } else {
                                // delete the pending item from the current list - if any
                                var notification_item = reservations.find('li#reservation-' + data.reservation.id);
                                if (notification_item.length) {
                                    notification_item.remove();
                                    // reservationsCountElem = reservationsToggle.find('span[data-count]');
                                    reservationsCount     = parseInt(reservationsCountElem.attr('data-count'));
                                    reservationsCount -= 1;
                                    reservationsCountElem.attr('data-count', reservationsCount);
                                    reservationsCountElem.text(reservationsCount);
                                }
                                toastr.options.timeOut = 6000; // How long the toast will display without user interaction
                                toastr.options.extendedTimeOut = 8000; // How long the toast will display after a user hovers over it
                                toastr.success('A reservation order for ' + data.reservation.restaurant.name + ' has been handled.');
                            }
                            $('#date-' + data.reservation.id).text(data.reservation.date);
                            $('#time-' + data.reservation.id).text(data.reservation.time);
                            $('#name-' + data.reservation.id).text(data.reservation.name);
                            $('#phone-' + data.reservation.id).text(data.reservation.phone);
                            $('#adult-' + data.reservation.id).text(data.reservation.adult);
                            $('#children-' + data.reservation.id).text(data.reservation.children);
                            $('#status-' + data.reservation.id).text(data.reservation.status);
                            switch (data.reservation.status) {
                                case 'pending':
                                    $('#status-' + data.reservation.id).attr('class', 'label bg-yellow');
                                    break;
                                case 'confirmed':
                                    $('#status-' + data.reservation.id).attr('class', 'label bg-green');
                                    break;
                                case 'canceled':
                                    $('#status-' + data.reservation.id).attr('class', 'label bg-red');
                                    break;
                            }
                            if (data.reservation.customer_requirement) {
                                var requirement = `
                                    <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="https://restaurant.local/note-md.png" data-lightbox="image-` + data.reservation.id + `" data-title="` +data.reservation.customer_requirement +`">
                                        <i class="material-icons">event_note</i>
                                    </a>
                                `;
                                $('#requirement-' + data.reservation.id).html(requirement);
                            }
                            else {
                                $('#requirement-' + data.reservation.id).empty();
                                $('#requirement-' + data.reservation.id).text('N/A');
                            }
                        }
                    });
                </script>
            @endforeach
        @endif
    @show
</body>

</html>