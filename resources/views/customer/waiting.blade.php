<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$restaurant->name}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: gray;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .full-height {
                height: 100vh;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
            .position-ref {
                position: relative;
            }
            .content {
                text-align: center;
            }
            .title {
                font-size: 36px;
                padding: 20px;
            }
            #lds-dual-ring {
              display: inline-block;
              position: fixed;
              top: 50%;
              left: 50%;
              margin-top: -32px;
              margin-left: -32px;
              z-index: 99;
              width: 64px;
              height: 64px;
            }
            #lds-dual-ring:after {
              content: " ";
              display: block;
              width: 46px;
              height: 46px;
              margin: 0;
              border-radius: 50%;
              border: 5px solid #fff;
              border-color: #fff transparent #fff transparent;
              animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
              0% {
                transform: rotate(0deg);
              }
              100% {
                transform: rotate(360deg);
              }
            }
        </style>
    </head>
    <body>
        <script>
            (function(d, s, id){
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) {return;}
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/messenger.Extensions.js";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'Messenger'));
        </script>
        <div id="lds-dual-ring"></div>
        <div class="flex-center position-ref full-height" style="display: none;">
            <form method="GET" action="{{$route}}" id="form-psid">
                @csrf
                <input type="hidden" name="psid" id="psid">
            </form>
        </div>
        <script>
            window.extAsyncInit = function() {
              // the Messenger Extensions JS SDK is done loading
                MessengerExtensions.getSupportedFeatures(function success(result) {
                  let features = result.supported_features;
                  if (features.indexOf("context") != -1) {
                    MessengerExtensions.getContext('1817679841861864',
                      function success(thread_context) {
                        // success
                        document.getElementById("psid").value = thread_context.psid;
                        // More code to follow
                        document.getElementById("form-psid").submit();
                      },
                      function error(err) {
                        console.log(err);
                      }
                    );
                  }
                }, function error(err) {
                  console.log(err);
                });
            };
        </script>
    </body>
</html>