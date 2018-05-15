@extends('restaurant.master')

@section('title')
	Create new restaurant
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
                                <li><a href="{{route('homepage')}}">Home</a></li>
                                <li><a href="{{route('restaurant.index')}}">My Restaurants</a></li>
                                <li><a href="{{route('restaurant.select-page')}}">Select facebook page</a></li>
                                <li class="active">Create</li>
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
                            @if(isset($page))
                            Create restaurant for <a href="{{$page['url']}}" target="_blank" style="text-decoration: none;"><img src="{{$page['page_profile_picture']}}" width="36" height="36" alt="{{$page['name']}}" style="border-radius: 50% !important;"> {{$page['name']}}</a>
                            @else
                            Create new restaurant
                            @endif
                        </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('restaurant.create')}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Nhập tên nhà hàng của bạn" value="{{isset($page) ? $page['name'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                @if(isset($page))
                                <input type="hidden" name="fb_page_id" class="form-control" required placeholder="Nhập Page ID của bạn" value="{{$page['id']}}">
                                @else
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="fb_page_id">Facebook Page ID</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="fb_page_id" class="form-control" required placeholder="Nhập Page ID của bạn">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect" style="margin-top: 0;">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div>
        </div>
    </div>
@endsection

@section('extra-script')
{{--         {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
        {{Html::script('bsbmd/plugins/raphael/raphael.min.js')}}
        {{Html::script('bsbmd/plugins/morrisjs/morris.js')}}
        {{Html::script('bsbmd/plugins/chartjs/Chart.bundle.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.resize.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.pie.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.categories.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.time.js')}}
        {{Html::script('bsbmd/plugins/jquery-sparkline/jquery.sparkline.js')}}
        {{Html::script('bsbmd/js/pages/index.js')}} --}}
@endsection