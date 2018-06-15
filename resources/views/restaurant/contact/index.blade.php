@extends('index')

@section('title')
  Thông tin liên hệ của nhà hàng - {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb">
                    <li><a href="{{route('homepage')}}">Trang chủ</a></li>
                    <li><a href="{{route('restaurant.index')}}">Nhà hàng của tôi</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Thông tin liên hệ</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Thông tin liên hệ
                        <small>Quản lý thông tin liên hệ, chi nhánh của nhà hàng</small>
                    </h2>
                </div>
                <div class="body">
                        @if($restaurant->contacts->count() > 0)

                        @foreach($restaurant->contacts as $no => $contact)
                        <form method="POST" action="{{route('contact.update', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix" style="border: 1px dotted grey; border-radius: 10px; margin-bottom: 5px;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-sm-4">
                                            <b>Cơ sở #{{$no + 1}}</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="hidden" name="id" required value="{{$contact->id}}">
                                                    <input type="text" name="name" class="form-control" required placeholder="Name" value="{{$contact->name}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <b>Địa chỉ</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" name="address" class="form-control" required placeholder="Address" value="{{$contact->address}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="map_url">Map URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">location_on</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="map_url" value="{{$contact->map_url}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row cleafix">
                                        <div class="col-sm-2">
                                            <label for="phone">Số điện thoại</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">phone</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" name="phone" class="form-control" required placeholder="Phone number" value="{{$contact->phone}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="secondary_phone">Số điện thoại (Khác)</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">phone</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" name="secondary_phone" class="form-control" placeholder="Optional" value="{{$contact->secondary_phone}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="opening_time">Giờ mở cửa</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="time" class="form-control" name="opening_time" placeholder="Optional" value="{{$contact->opening_time}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="closing_time">Giờ đóng cửa</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="time" class="form-control" name="closing_time" placeholder="Optional" value="{{$contact->closing_time}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" style="text-align: center;">
                                            <label>Thao tác</label>
                                            <div><a href="{{route('contact.delete', ['slug' => $restaurant->slug, 'contact_id' => $contact->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Xoá">
                                                <i class="material-icons">delete</i>
                                            </a>&nbsp;<button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Lưu">
                                                <i class="material-icons">save</i>
                                            </button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @endif
                        <form method="POST" action="{{route('contact.create', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix" style="border: 1px dotted grey; border-radius: 10px; margin-bottom: 5px;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-sm-4">
                                            <label for="name">Tạo thêm chi nhánh/cơ sở</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="name" required placeholder="Your branch name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="address">Địa chỉ</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="address" required placeholder="Your branch address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="map_url">Map URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">location_on</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="map_url" placeholder="Optional">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-2">
                                            <label for="phone">Số điện thoại</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">phone</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="phone" required placeholder="Primary Phone Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="secondary_phone">Số điện thoại (Khác)</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">phone</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="secondary_phone" placeholder="Optional">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="opening_time">Giờ mở cửa</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="time" class="form-control" name="opening_time" placeholder="Optional" class="timepicker">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="closing_time">Giờ đóng cửa</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="time" class="form-control" name="closing_time" placeholder="Optional" class="timepicker">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" style="text-align: center;">
                                            <label>Thao tác</label>
                                            <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number">
                                                <i class="material-icons">add</i>
                                            </button></div>
                                        </div>
                                    </div>
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
        {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
@endsection