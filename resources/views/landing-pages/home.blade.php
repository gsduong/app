@extends('landing-pages.master')

@section('content')
    <div class="landing-page">
        @include('landing-pages.pieces.topbar-home')
        <div class="wrapper">
            <div class="header header-filter" style="background-image: url('{{ asset('bsbmd/images/restaurant.jpg') }}');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="title">Đón thêm nhiều khách đến quán của bạn với BookNow</h1>
                            <h4>BookNow giúp bạn quản lý đặt bàn, thực đơn, đơn hàng đồ ăn ship và hơn thế nữa. 
                            </h4>
                            <h4>Bạn không cần dành quá nhiều thời gian để nghe hay nhận cuộc gọi từ khách hàng, hãy để chatbot của chúng tôi làm điều đó giúp bạn.</h4>
                            <a href="https://www.facebook.com/GsDuong-Restaurant-157361414881471" class="btn btn-danger btn-raised btn-lg">
                                <i class="fa fa-play"></i> Xem video
                            </a>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main main-raised">
                <div class="container">
                    <div class="section text-center section-landing" style="padding-top: 10px;">
                        <div class="row">
                            <h2 class="title">BookNow giúp được gì cho bạn?</h2>
                        </div>
                        <div class="features" style="padding-top: 0px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info" style="padding-top: 0px;">
                                        <div class="icon icon-danger">
                                            <i class="material-icons">menu</i>
                                        </div>
                                        <h4 class="info-title">Quản lý Menu</h4>
                                        <p>Dễ dàng lên thực đơn cho nhà hàng của bạn với giao diện quản lý menu tuyệt vời. Bạn cũng có thể dễ dàng cho khách hàng biết thông tin về địa chỉ nhà hàng, số điện thoại và giờ mở cửa.</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info" style="padding-top: 0px;">
                                        <div class="icon icon-primary">
                                            <i class="material-icons">chat</i>
                                        </div>
                                        <h4 class="info-title">Chatbot</h4>
                                        <p>Chatbot thông minh của BookNow giúp bạn nhận các yêu cầu từ khách hàng như đặt bàn, order đồ ăn ship, ... Khách hàng có thể xem thực đơn cũng như biết được những thông tin về nhà hàng như địa chỉ, giờ mở cửa, khuyến mãi. 
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info" style="padding-top: 0px;">
                                        <div class="icon icon-success">
                                            <i class="material-icons">restaurant_menu</i>
                                        </div>
                                        <h4 class="info-title">Quản lý Booking</h4>
                                        <p>Ngay khi chatbot nhận yêu cầu đặt bàn từ khách hàng, bạn sẽ được nhận thông báo về yêu cầu này. Giao diện quản lý tuyệt vời sẽ giúp bạn dễ dàng quản lý tốt lịch đặt bàn cho nhà hàng của bạn.</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info" style="padding-top: 0px;">
                                        <div class="icon icon-danger">
                                            <i class="material-icons">local_shipping</i>
                                        </div>
                                        <h4 class="info-title">Quản lý đơn đặt hàng</h4>
                                        <p>Giống như quản lý đặt bàn, chatbot giúp bạn nhận yêu cầu order từ khách hàng và cung cấp thông tin về địa chỉ và số điện thoại khách hàng cho bạn. Hãy chắc chắn rằng bạn có thể xử lý được thật nhiều đơn hàng nhé!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <footer class="footer">
                <div class="container">
                    <nav class="pull-left">
                        <ul>
                            <li>
                                <a href="https://www.facebook.com/GsDuong-Restaurant-157361414881471">
                                    Fanpage BookNow
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright pull-right" style="font-size: 70%;">
                        Theme © 2016, made with <i class="fa fa-heart heart"></i> by Creative Tim
                    </div>
                </div>
            </footer>

        </div>
    </div>
@endsection