@extends('restaurant.master')

@section('title')
	Sửa thông tin nhà hàng
@endsection

@section('extra-css')

@endsection

@section('content')
	<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div>
            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="block-header">
                            <ol class="breadcrumb">
                                <li><a href="{{route('homepage')}}">Trang chủ</a></li>
                                <li><a href="{{route('restaurant.index')}}">Nhà hàng của tôi</a></li>
                                <li><a href="{{route('restaurant.show' , $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                                <li class="active">Sửa thông tin</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div>
            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Sửa thông tin nhà hàng
                        </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('restaurant.update', $restaurant->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="name">Tên</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required value="{{$restaurant->name}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xs-6 col-sm-6">
                                    <label for="image_file">Ảnh nền hiển thị trong chatbot</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="file" name="image_file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xs-6 col-sm-6">
                                    <label>Ảnh hiện tại</label>
                                    <div class="image">
                                        <img src="{{$restaurant->background_url ? $restaurant->background_url : $restaurant->avatar}}" alt="{{$restaurant->name}}" width="48" height="48" style="border-radius: 50%;">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;" onClick="this.form.submit(); this.disabled=true;">Lưu</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div>
        </div>
    </div>
@endsection

@section('extra-script')
@endsection