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
        {{ Html::style('css/customer.css')}}
        {{ Html::style('css/date-time-picker.min.css')}}
        <style>
        * {margin:0;}
        html {height:100%}
        body {min-height:100%}
        </style>
         <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    @show
    @yield('extra-css')
</head>

<body class="theme-green">
    <script>
        (function(d, s, id){
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/messenger.Extensions.js";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'Messenger'));
    </script>
    <section class="content">
        @yield('content')
    </section>

    @section('script')
        {{Html::script('bsbmd/plugins/jquery/jquery.min.js')}}
        {{Html::script('bsbmd/plugins/bootstrap/js/bootstrap.js')}}
        {{Html::script('bsbmd/plugins/node-waves/waves.js')}}
        {{Html::script('js/toastr.min.js')}}
        {{Html::script('js/lightbox.js')}}
        {{Html::script('js/date-time-picker.min.js')}}
    @show    
    @yield('extra-script')
    @include('message.message')
    @section('script-bottom')
        <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fitImagesInViewport': true
            });
        </script>
        <script>
        // When the user scrolls down 20px from the top of the document, show the button
        // window.onscroll = function() {scrollFunction()};

        // function scrollFunction() {
        //     if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        //         document.getElementById("topBtn").style.display = "block";
        //     } else {
        //         document.getElementById("topBtn").style.display = "none";
        //     }
        // }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
        </script>
        <script>
            window.extAsyncInit = function() {
              // the Messenger Extensions JS SDK is done loading
                MessengerExtensions.getUserID(function success(user_ids) {
                  // User ID was successfully obtained. 
                  let psid = user_ids.psid;
                  console.log(psid);
                  $('a#psid').text(psid);
                }, function error(err, errorMessage) {      
                  // Error handling code
                });
            };
        </script>
    @show
</body>

</html>