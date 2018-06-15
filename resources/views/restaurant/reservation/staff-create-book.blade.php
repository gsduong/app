@extends('index')

@section('title')
	Tạo mới đơn đặt bàn cho {{$restaurant->name}}
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
                    <li class="active">Tạo mới đơn đặt bàn</li>
                </ol>
            </div>
        </div>
    </div>
<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
            <form method="POST" action="{{route('reservation.create', ['restaurant_slug' => $restaurant->slug])}}">
                @csrf
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                        <b>Ngày</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="date" class="form-control" name="date" value="{{ old('date') }}" required="true" min={{date('Y-m-d')}}>
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
                                <input type="time" value="{{ old('time') }}" class="form-control" name="time" placeholder="Ex: 23:59" required="true">
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
                                <input type="text" name="name" class="form-control" placeholder="Please provide customer's name" required="true" value="{{ old('name') }}">
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
                                <input type="text" name="phone" class="form-control" placeholder="Please provide customer's phone number" required="true" value="{{ old('phone') }}">
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
                                <input type="text" name="email" class="form-control" placeholder="Optional" value="{{ old('email') }}">
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
                                <input type="number" name="adult" class="form-control" placeholder="Number of adults" required="true" min="1" value="{{ old('adult') }}">
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
                                <input type="number" name="children" class="form-control" placeholder="Number of children" value="{{ old('children') }}" min="0">
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
                                <input type="text" name="requirement" class="form-control" placeholder="Customer's additional requirement" value="{{ old('requirement') }}">
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
                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'checked' : ''}} value="{{$contact->id}}">
                                        <label for="address_{{$no + 1}}">{{$contact->address}}</label>
                                    </div>

                                    @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-block btn-lg btn-default waves-effect">BOOK</button>
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