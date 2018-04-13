<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">BookNow</a>
        </div>

        <div class="collapse navbar-collapse" id="navigation-example">
            <ul class="nav navbar-nav navbar-right">
                @guest
                <li>
                    <a href="{{ route('provider', 'facebook') }}">
                        <i class="fa fa-facebook-square"></i>
                        Đăng nhập
                    </a>
                </li>
                @else
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="fa fa-facebook-square"></i>
                        Đăng xuất
                    </a>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>