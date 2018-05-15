@extends('restaurant.master')

@section('title')
	My restaurants
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
                            <li class="active">My Restaurants</li>
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
            <ul class="list-group">
                <div class="row clearfix" style="margin-left: 0; margin-right: 0;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="{{route('restaurant.select-page')}}" title="Add new restaurant" style="text-decoration: none;">
                            <div class="card transparent_class" style="margin-bottom: 10px; border: 5px #D3D3D3 dashed; border-radius: 5px; text-align: center;">
                                <p class="vertical-align-custom">Add new restaurant</p>
                            </div>
                        </a>
                    </div>
                </div>
            </ul>
        </div>
        <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
    </div>
    <div class="row clearfix">
        <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
        <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
            <ul class="list-group" style="overflow-y: auto; max-height: 475px;">
                @foreach($restaurants as $restaurant)
                <div class="row clearfix" style="margin-left: 0; margin-right: 0;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" style="margin-bottom: 10px;">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                        <div class="image">
                                            <img src="{{$restaurant['avatar']}}" width="36" height="36" alt="{{$restaurant['name']}}" style="border-radius: 50% !important;">
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                        <h2>
                                            {{$restaurant['name']}} <small>{{$restaurant['created_at']}}</small>
                                        </h2>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                        <a href="{{route('restaurant.delete', $restaurant->id)}}" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="material-icons vertical-align-custom">delete</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </ul>
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