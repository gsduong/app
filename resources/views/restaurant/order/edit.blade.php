@extends('customer.index')

@section('title')
    {{$restaurant->name}} - Food Order
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header" style="text-align: center;">
                    <h2>
                        <small><a {{-- href="{{route('customer.show-form-create-order', ['restaurant_slug' => $restaurant->slug, 'customer_psid' => $customer->app_scoped_id])}}" --}}>Đơn hàng ship đồ ăn - {{$restaurant->name}}</a></small>
                    </h2>
                </div>
                <div class="body">
                    @if($order->items->count())
                    <!-- Nav tabs -->
                    @if($order->status == "pending")
                    <form method="POST" id="form" action="{{route('order.confirm', ['restaurant_slug' => $restaurant->slug, 'order_id' => $order->id])}}">
                    @elseif($order->status == "confirmed")
                    <form method="POST" id="form" action="{{route('order.deliver', ['restaurant_slug' => $restaurant->slug, 'order_id' => $order->id])}}">
                    @else
                    <form method="POST" id="form" action="">
                    @endif
                        @csrf
                        <input type="hidden" name="order-id" value="{{$order->id}}">

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in">
                            @if($order->items->count())
                                <div class="row">
                                @foreach($order->items as $idx => $item)
                                    @if($item->ship)
                                    <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                                        <div class="thumbnail">
                                            <div class="image">
                                                <a href="{{$item->image_url}}" data-lightbox="image-{{$item->id}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" alt="{{$item->name}}"></a>
                                            </div>
                                            {{-- <img src="{{$item->image_url}}"> --}}
                                            <div class="caption">
                                                <h4>{{$item->name}}</h4>
                                                <p><span class="label label-success">{{$item->money()}} đ</span>
                                                    @if($item->ship)
                                                        <i class="material-icons col-red pull-right" title="Nhận ship">local_shipping</i>
                                                    @endif
                                                </p>
                                                <p>
                                                    {{$item->description}}
                                                </p>
                                                @if($item->item_url)
                                                <p><small><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></small></p>
                                                @endif
                                                {{-- <label for="name">Quantity</label> --}}
                                                <div class="row">
                                                    <div class="col-xs-12" style="margin-bottom: 0;">
                                                        <div class="input-group" style="margin-bottom: 5px;">
                                                            <input readonly disabled  class="form-control input-number" type="text" style="border: 1px solid #D3D3D3; height: 29px; text-align: center;" value="{{$order->items->find($item->id)->pivot->qty}}">
                                                        </div>  
                                                    </div>
                                                </div>
                                                <p>Price: <span class="label label-danger" style="vertical-align: middle;" id="total-{{$item->id}}">{{(int) ($order->items->find($item->id)->pivot->price * $order->items->find($item->id)->pivot->qty)}}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                                    <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <b>Tên khách hàng</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">person</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" disabled name="name" id="name" onfocusout="updateLabel(this)" class="form-control" placeholder="Please provide your name" required="true" value="{{ $order->customer_name ? $order->customer_name : old('name') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Số điện thoại</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">phone</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" disabled name="phone" id="phone" onfocusout="updateLabel(this)" class="form-control" placeholder="Please provide your phone number" required="true" value="{{ $order->customer_phone ? $order->customer_phone : old('phone') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Địa chỉ giao hàng</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">location_on</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" onfocusout="updateLabel(this)" disabled class="form-control" name="address" placeholder="Please provide your address" id="address" value="{{ $order->customer_address ? $order->customer_address : old('address') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($restaurant->contacts->count() > 0)
                                                        <div class="col-xs-6">
                                                            <b>Chi nhánh</b>
                                                            <div class="input-group" style="margin-bottom: 0;">
                                                                <div class="demo-radio-button">
                                                                    <div class="row">
                                                                    @foreach($restaurant->contacts as $no => $contact)
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->branch_id == $contact->id ? 'block' : 'none'}};" >
                                                                        <input name="branch_id" type="radio" id="address_{{$no + 1}}" {{$order->branch_id == $contact->id ? 'checked' : ''}} value="{{$contact->id}}">
                                                                        <label for="address_{{$no + 1}}">{{$contact->address}}</label>
                                                                    </div>

                                                                    @endforeach
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-xs-6">
                                                        <b>Trạng thái</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <div class="demo-radio-button">
                                                                <div class="row">
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->status == 'pending' ? 'block' : 'none'}};" >
                                                                        <input name="status" type="radio" id="pending" {{$order->status == "pending" ? 'checked' : ''}}>
                                                                        <label for="pending">Chờ duyệt</label>
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->status == "confirmed" ? 'block' : 'none'}};" >
                                                                        <input name="status" type="radio" id="confirmed" {{$order->status == "confirmed" ? 'checked' : ''}}>
                                                                        <label for="confirmed">Xác nhận</label>
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->status == "delivering" ? 'block' : 'none'}};" >
                                                                        <input name="status" type="radio" id="delivering" {{$order->status == "delivering" ? 'checked' : ''}}>
                                                                        <label for="delivering">Đang giao hàng</label>
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->status == "delivered" ? 'block' : 'none'}};" >
                                                                        <input name="status" type="radio" id="delivered" {{$order->status == "delivered" ? 'checked' : ''}}>
                                                                        <label for="delivered">Đã giao hàng thành công</label>
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$order->status == "canceled" ? 'block' : 'none'}};" >
                                                                        <input name="status" type="radio" id="canceled" {{$order->status == "canceled" ? 'checked' : ''}}>
                                                                        <label for="delivered">Huỷ</label>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Note</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">event_note</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" disabled class="form-control" name="requirement" value="{{ $order->customer_note ? $order->customer_note : old('requirement') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <div class="row">
                                <div class="col-xs-12" style="text-align: center;">
                                    Không có item nào
                                </div>
                            </div>
                            @endif
                        </div>                        
                    </div>
                    @if($order->status == "pending")
                    <button type="submit" id="orderBtn" title="Confirm Order" style="margin-left: -75px;"><i class="material-icons">check</i></button>
                    @elseif($order->status == "confirmed")
                    <button type="submit" id="orderBtn" title="Deliver Order" style="margin-left: -75px;"><i class="material-icons">local_shipping</i></button>
                    @else
                    <button disabled id="orderBtn" style="margin-left: -75px;"><i class="material-icons">local_shipping</i></button>

                    @endif
                    </form>
                    @else
                        <div class="row">
                            <div class="col-xs-12" style="text-align: center;">
                                Menu tạm thời chưa được cập nhật
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <button onclick="topFunction()" id="topBtn" title="Go to top" style="margin-left: 5px;"><i class="material-icons">arrow_upward</i></button>
            <span class="label label-danger" id="total-price">{{$order->money()}}</span>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
@endsection