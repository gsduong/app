@extends('index')

@section('title')
	Chỉnh sửa đơn đặt bàn cho {{$restaurant->name}}
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
                    <li><a href="{{route('reservation.index', $restaurant->slug)}}">Đơn đặt bàn</a></li>
                    <li class="active">Chỉnh sửa đơn đặt bàn</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{$restaurant->name}}
                        <small>Chỉnh sửa đơn đặt bàn cho {{$restaurant->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('reservation.update', ['restaurant_slug' => $restaurant->slug])}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="name">Tên khách hàng</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Name" value={{$reservation->customer_name}}>
                                            <input type="hidden" name="id" required value={{$reservation->id}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="price">Điện thoại</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="phone" class="form-control" required placeholder="Phone" value={{$reservation->customer_phone}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="date">Ngày</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="date" class="form-control" required value={{$reservation->date}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="time">Thời gian</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="time" name="time" class="form-control" required value={{$reservation->time}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="adult">Số người lớn</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" required name="adult" class="form-control" min="1" value={{$reservation->adult}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="children">Số trẻ em</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="children" class="form-control" min="0" value={{$reservation->children}}>
                                        </div>
                                    </div>
                                </div>
                                @if($restaurant->contacts->count() > 0)
                                    <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                        <h2 class="card-inside-title">Địa chỉ</h2>
                                        <div class="demo-radio-button">
                                        @foreach($restaurant->contacts as $no => $contact)
                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'required' : ''}} value="{{$contact->id}}" {{$reservation->address_id == $contact->id ? 'checked' : ''}}>
                                        <label for="address_{{$no + 1}}"> {{$contact->name}} - {{$contact->address}}</label>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <h2 class="card-inside-title">Trạng thái</h2>
                                    <div class="demo-radio-button">
                                    <input name="status" type="radio" id="status_1" value="pending" {{$reservation->status == 'pending' ? 'checked' : ''}}>
                                    <label for="status_1">Chờ duyệt</label>
                                    <input name="status" type="radio" id="status_2" value="confirmed" {{$reservation->status == 'confirmed' ? 'checked' : ''}}>
                                    <label for="status_2">Đặt bàn thành công</label>
                                    <input name="status" type="radio" id="status_3" value="cancelled" {{$reservation->status == 'cancelled' ? 'checked' : ''}}>
                                    <label for="status_3">Huỷ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <label for="customer_requirement">Ghi chú</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="2" name="customer_requirement" class="form-control no-resize" placeholder="Optional">{{$reservation->customer_requirement}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Lưu</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection