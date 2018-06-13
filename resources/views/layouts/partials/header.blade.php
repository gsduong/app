<!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ route('homepage')}}" alt="Home Page"><img class="img-responsive" src="{{asset('images/logo.png')}}" alt="booknow" border="0" width="100" height="100"></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown" id="dropdown-reservations-{{$restaurant->fb_page_id}}">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">event_note</i>
                            <span class="label-count" data-count="{{$restaurant->pending_reservations()->count()}}">
                                @if(isset($restaurant) && $restaurant->pending_reservations()->count())
                                {{$restaurant->pending_reservations()->count()}}
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu list-group" id="dropdown-reservations-wrapper">
                            <li class="header">ĐƠN ĐẶT BÀN</li>
                            <li class="body">
                                <ul class="menu" id="dropdown-menu-reservations">
                                    @if(isset($restaurant) && $restaurant->pending_reservations()->count())
                                    @foreach($restaurant->pending_reservations() as $no => $book)
                                        <li id="reservation-{{$book->id}}" style="display: {{$no > 5 ? 'none' : 'block'}};">
                                            <a href="{{route('reservation.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $book->id])}}" class=" waves-effect waves-block">
                                                <div class="icon-circle bg-light-green">
                                                    @if($book->created_by_bot)
                                                    <img src="{{asset('bot-icon.png')}}" width="36" height="36" alt="Bot" style="border-radius: 50%;">
                                                    @else
                                                    <img src="{{$book->last_editor()->avatar}}" width="36" height="36" alt="Bot" style="border-radius: 50%;">
                                                    @endif
                                                </div>
                                                <div class="menu-info">
                                                    <h4>
                                                        @if($book->created_by_bot)
                                                        Đơn đặt bàn qua chatbot
                                                        @else
                                                        Đơn đặt bàn trực tiếp
                                                        @endif
                                                    </h4>
                                                    <p>
                                                        <i class="material-icons">access_time</i> {{$book->updated_at}}
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </li>
                            @if(isset($restaurant))
                            <li class="footer">
                                <a href="{{route('reservation.index', $restaurant->slug)}}">Xem tất cả đơn đặt bàn</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li class="dropdown" id="dropdown-orders-{{$restaurant->fb_page_id}}">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">local_shipping</i>
                            <span class="label-count" data-count="{{$restaurant->pending_orders()->count()}}">
                                @if(isset($restaurant) && $restaurant->pending_orders()->count())
                                {{$restaurant->pending_orders()->count()}}
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">ĐƠN HÀNG SHIP ĐỒ</li>
                            <li class="body">
                                <ul class="menu" id="dropdown-menu-orders">
                                    @if(isset($restaurant) && $restaurant->orders()->count())
                                    @foreach($restaurant->pending_orders() as $no => $order)
                                        <li id="order-{{$order->id}}" style="display: {{$no > 5 ? 'none' : 'block'}};">
                                            <a href="{{-- {{route('order.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $order->id])}} --}}" class=" waves-effect waves-block">
                                                <div class="icon-circle bg-light-green">
                                                    @if($order->created_by_bot)
                                                    <img src="{{asset('bot-icon.png')}}" width="36" height="36" alt="Bot" style="border-radius: 50%;">
                                                    @else
                                                    <img src="{{$order->last_editor()->avatar}}" width="36" height="36" alt="Bot" style="border-radius: 50%;">
                                                    @endif
                                                </div>
                                                <div class="menu-info">
                                                    <h4>
                                                        @if($order->created_by_bot)
                                                        Đơn hàng ship đồ ăn khởi tạo bởi chatbot
                                                        @else
                                                        Đơn hàng ship đồ ăn tạo bởi nhân viên
                                                        @endif
                                                    </h4>
                                                    <p>
                                                        <i class="material-icons">access_time</i> {{$order->updated_at}}
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </li>
                            @if(isset($restaurant))
                            <li class="footer">
                                <a href="{{route('order.index', $restaurant->slug)}}">Xem tất cả đơn hàng</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <!-- #END# Tasks -->
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>