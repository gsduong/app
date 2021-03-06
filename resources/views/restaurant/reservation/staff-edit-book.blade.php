@extends('index')

@section('title')
	Cập nhật đơn đặt bàn cho {{$restaurant->name}}
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
                    <li><a href="{{route('reservation.index', $restaurant->slug)}}">Đơn đặt bàn</a></li>
                    <li class="active">Cập nhật đơn đặt bàn</li>
                </ol>
            </div>
        </div>
    </div>
<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
            <form method="POST" action="{{route('reservation.update', ['restaurant_slug' => $restaurant->slug])}}">
                @csrf
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Ngày</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="date" class="form-control" name="date" value="{{ $reservation->date }}" required="true" min={{date('Y-m-d')}}>
                                <input type="hidden" name="id" value="{{ $reservation->id }}" required="true" min={{date('Y-m-d')}}>
                            </div>
                        </div>
                        <label id="date-error" class="validation-error-label" for="date"><small>{{ $errors->first('date') }}</small></label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Thời gian</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">access_time</i>
                            </span>
                            <div class="form-line">
                                <input type="time" value="{{ $reservation->time }}" class="form-control" name="time" placeholder="Ex: 23:59" required="true">
                            </div>
                        </div>
                        <label id="time-error" class="validation-error-label" for="time"><small>{{ $errors->first('time') }}</small></label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Tên khách hàng</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="name" class="form-control" placeholder="Please provide customer's name" required="true" value="{{ $reservation->customer_name }}">
                            </div>
                        </div>
                        <label id="name-error" class="validation-error-label" for="name"><small>{{ $errors->first('name') }}</small></label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Số điện thoại</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">phone</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="phone" class="form-control" placeholder="Please provide customer's phone number" required="true" value="{{ $reservation->customer_phone }}">
                            </div>
                        </div>
                        <label id="phone-error" class="validation-error-label" for="phone"><small>{{ $errors->first('phone') }}</small></label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Email</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="email" class="form-control" placeholder="Optional" value="{{ $reservation->email }}">
                            </div>
                        </div>
                        <label id="email-error" class="validation-error-label" for="email"><small>{{ $errors->first('email') }}</small></label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Số người lớn</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">people</i>
                            </span>
                            <div class="form-line">
                                <input type="number" name="adult" class="form-control" placeholder="Number of adults" required="true" min="1" value="{{ $reservation->adult }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Số trẻ em</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">child_care</i>
                            </span>
                            <div class="form-line">
                                <input type="number" name="children" class="form-control" placeholder="Number of children" value="{{ $reservation->children }}" min="0">
                            </div>
                        </div>
                        <label id="children-error" class="validation-error-label" for="children"><small>{{ $errors->first('children') }}</small></label>
                    </div>
                    <div class="col-xs-12">
                        <b>Ghi chú</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">event_note</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="requirement" class="form-control" placeholder="Your additional requirement" value="{{ $reservation->customer_requirement }}">
                            </div>
                        </div>
                    </div>
                    @if($restaurant->contacts->count() > 0)
                        <div class="col-xs-12">
                            <b>Cơ sở</b>
                            <div class="input-group" style="margin-bottom: 0;">
                                <div class="demo-radio-button">
                                    <div class="row">
                                    @foreach($restaurant->contacts as $no => $contact)
                                    <div class="col-xs-6" style="padding-left: 11px; margin-bottom: 5px;">
                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$reservation->address_id == $contact->id ? 'checked' : ''}} value="{{$contact->id}}">
                                        <label for="address_{{$no + 1}}">{{$contact->address}}</label>
                                    </div>

                                    @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-12">
                        <b>Trạng thái đơn đặt bàn</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="demo-radio-button">
                                <div class="row">
                                    <div class="col-xs-6" style="padding-left: 11px; margin-bottom: 5px;">
                                        <input name="status" type="radio" id="status_1" value="pending" {{$reservation->status == 'pending' ? 'checked' : ''}}>
                                        <label for="status_1">Chờ duyệt</label>
                                    </div>
                                    <div class="col-xs-6" style="padding-left: 11px; margin-bottom: 5px;">
                                        <input name="status" type="radio" id="status_2" value="confirmed" {{$reservation->status == 'confirmed' ? 'checked' : ''}}>
                                        <label for="status_2">Đặt bàn thành công</label>
                                    </div>
                                    <div class="col-xs-6" style="padding-left: 11px; margin-bottom: 5px;">
                                        <input name="status" type="radio" id="status_3" value="canceled" {{$reservation->status == 'canceled' ? 'checked' : ''}}>
                                        <label for="status_3">Huỷ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-block btn-lg btn-success waves-effect">CẬP NHẬT</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section('extra-script')

@endsection