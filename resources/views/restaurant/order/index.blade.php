@extends('index')

@section('title')
  Quản lý đơn hàng order đồ ăn online - {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Trang chủ</a></li>
                    <li><a href="{{route('restaurant.index')}}">Nhà hàng của tôi</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Quản lí đơn hàng order đồ ăn online</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            {{$orders->appends(['date' => Input::get('date'), 'name' => Input::get('name'), 'phone' => Input::get('phone'), 'status' => Input::get('status')])->links()}}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Đơn hàng order đồ ăn
                        <small>Quản lý đơn hàng order đồ ăn cho nhà hàng của bạn</small>
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if($orders->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                @if($restaurant->contacts->count())
                                <th style="text-align: center;">Chi nhánh</th>
                                @endif
                                <th style="text-align: center;">Tên KH</th>
                                <th style="text-align: center;">SĐT</th>
                                <th style="text-align: center;">Địa chỉ</th>
                                <th style="text-align: center;">Tổng</th>
                                <th style="text-align: center;">Trạng thái</th>
                                <th style="text-align: center;">Note</th>
                                <th style="text-align: center;">Tạo bởi</th>
                                <th style="text-align: center;">Lần cuối chỉnh sửa bởi</th>
                                <th style="text-align: center;">Tuỳ chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $no => $order)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                @if($order->branch_id)
                                <td style="text-align: center; vertical-align: middle;" id="order-branch-{{$order->id}}">{{$order->branch->name}}</td>
                                @endif
                                <td style="text-align: center; vertical-align: middle;" id="order-name-{{$order->id}}">{{$order->customer->name}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="order-phone-{{$order->id}}">{{$order->customer_phone}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="order-address-{{$order->id}}">{{$order->customer_address}}</td>
                                <td style="text-align: center; vertical-align: middle;" class="price" id="order-total-{{$order->id}}">{{$order->money()}}</td>
                                <td style="text-align: center; vertical-align: middle;"><span class="{{$order->getLabelClass()}}" id="order-status-{{$order->id}}">{{$order->status}}</span></td>
                                @if($order->customer_note)
                                <td style="text-align: center; vertical-align: middle;" id="order-note-{{$order->id}}">
                                    <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="{{asset('note-md.png')}}" data-lightbox="image-{{$order->id}}" data-title="{{$order->customer_note}}"><i class="material-icons">event_note</i></a>
                                </td>
                                @else
                                <td style="text-align: center; vertical-align: middle;" id="order-note-{{$order->id}}">Không có</td>
                                @endif
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($order->created_by_bot)
                                    <div class="image">
                                        <img src="{{asset('bot-icon.png')}}" width="36" height="36" alt="Bot" title="Bot" style="border-radius: 50% !important;">
                                    </div>
                                    @else
                                    <div class="image">
                                        <img src="{{$order->creator()->avatar}}" width="36" height="36" alt="{{$order->creator()->name}}" title="{{$order->creator()->name}}" style="border-radius: 50% !important;">
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;"  id="order-last-edit-{{$order->id}}">
                                    @if($order->last_editor())
                                    <div class="image">
                                        <img src="{{$order->last_editor()->avatar}}" width="36" height="36" title="{{$order->last_editor()->name}}" alt="{{$order->last_editor()->name}}" style="border-radius: 50% !important;" id="image-{{$order->id}}">
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{route('order.cancel', ['restaurant_slug' => $restaurant->slug, 'order' => $order->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Are you sure you want to cancel this order?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('order.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'order_id' => $order->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" title="Confirm this order">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    &nbsp;
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    Không có
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{route('order.index', ['restaurant_slug' => $restaurant->slug])}}">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="date" class="form-control" name="date" value="{{isset($today) ? $today : Input::get('date')}}" style="padding-left: 5px; border-radius: 10px;" placeholder="Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <select name="status" class="form-line no-border-bottom" style="height: 35px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); border: 1px !important; border-radius: 15px; margin-bottom: 5px;">
                                <option value="" disabled selected>Status</option>
                                <option value="pending" {{Input::get('status') == 'pending' ? 'selected' : ''}}>Chờ duyệt</option>
                                <option value="confirmed" {{Input::get('status') == 'confirmed' ? 'selected' : ''}}>Xác nhận</option>
                                <option value="delivering" {{Input::get('status') == 'delivering' ? 'selected' : ''}}>Đang giao</option>
                                <option value="delivered" {{Input::get('status') == 'delivered' ? 'selected' : ''}}>Đã giao</option>
                                <option value="canceled" {{Input::get('status') == 'canceled' ? 'selected' : ''}}>Đã huỷ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="name" value="{{Input::get('name')}}" placeholder=" Name" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{Input::get('phone')}}" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Lọc</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><a href="{{route('order.index', $restaurant->slug)}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Xoá lọc</a>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

@endsection