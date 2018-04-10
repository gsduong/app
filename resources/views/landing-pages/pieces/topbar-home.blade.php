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
                <li>
                    <a href="{{ route('register') }}">
                        <i class="material-icons">person_add</i>
                        Đăng ký
                    </a>
                </li>
                <li>
                    <a href="{{ route('login') }}">
                        <i class="material-icons">person_outline</i>
                        Đăng nhập
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/GsDuong-Restaurant-157361414881471" target="_blank" class="btn btn-simple btn-white btn-just-icon" title="Fanpage của BookNow">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>