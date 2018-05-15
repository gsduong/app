@extends('restaurant.master')

@section('title')
	Select your page
@endsection

@section('extra-css')

@endsection

@section('content')
	<div class="container-fluid">
{{--         <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12"></div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="block-header">
                    <div class="row clearfix" style="margin-left: 0; margin-right: 0;">

                            <h2 style="margin-left: auto !important; margin-right: auto !important;">Connect to your page from the list below
                            </h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12"></div>
        </div> --}}
        <div class="row clearfix">
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                @if(count($pages) == 0)
                <small style="display: block;margin-left: auto;margin-right: auto;width: 40%; text-align: center;">No page found
                </small>
                @else
                    <ul class="list-group" style="overflow-y: auto; max-height: 715px;">
                        @foreach($pages as $page)
                        <div class="row clearfix" style="margin-left: 0; margin-right: 0;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card" style="margin-bottom: 10px;">
                                    <div class="header">
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <div class="image">
                                                    <img src="{{$page['page_profile_picture']}}" width="36" height="36" alt="{{$page['name']}}" style="border-radius: 50% !important;">
                                                </div>
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
                                                <h2>
                                                    {{$page['name']}} <small>{{$page['category']}}</small>
                                                </h2>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                                                <form action="{{route('restaurant.show-form-create-with-id')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="page_id" required value="{{$page['id']}}">
                                                    <button type="submit" class="btn btn-success waves-effect">SELECT</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                @endif
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