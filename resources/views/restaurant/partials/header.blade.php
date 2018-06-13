    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ route('homepage')}}" alt="Trang chủ"><img class="img-responsive" src="{{asset('images/logo.png')}}" alt="booknow" border="0" width="100" height="100"></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right" style="text-align:center;">
                    <li>
                        <a href="{{ route('logout') }}" style="min-height: 30px; margin-bottom: 5px; margin-top: 10px;">
                            <div class="image">
                                <img src="{{auth()->user()->avatar}}" width="36" height="36" alt="{{auth()->user()->name}}" style="border-radius: 50% !important;"> Đăng xuất
                            </div>
                        </a>
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>