@extends('index')

@section('title')
  Thành viên của {{$restaurant->name}}
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
                    <li class="active">Thành viên</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Nhân viên của {{$restaurant->name}}
                        <small>Bạn có thể quản lí vai trò của nhân viên nhà hàng thông qua <a href="{{'https://www.facebook.com/' . $restaurant->fb_page_id. '/settings/?tab=admin_roles'}}" target="_blank">Facebook Page Settings</a></small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{'https://www.facebook.com/' . $restaurant->fb_page_id. '/settings/?tab=admin_roles'}}" class=" waves-effect waves-block" target="_blank">Đi tới Facebook Page</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Thành viên</h2>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="image">
                                    <a href="{{'https://www.facebook.com/' . auth()->user()->provider_id}}" target="_blank" title="{{auth()->user()->name}}"><img src="{{auth()->user()->avatar}}" width="36" height="36" alt="{{auth()->user()->name}}" style="border-radius: 50% !important;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Quản trị viên</h2>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="image">
                                @foreach($restaurant->admins() as $admin)
                                
                                    <a href="{{'https://www.facebook.com/' . $admin->provider_id}}" target="_blank" title="{{$admin->name}}"><img src="{{$admin->avatar}}" width="36" height="36" alt="{{$admin->name}}" style="border-radius: 50% !important;"></a>
                                
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Nhân viên</h2>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="image">
                                @foreach($restaurant->staffs() as $staff)
                                     <a href="{{'https://www.facebook.com/' . $staff->provider_id}}" target="_blank" title="{{$staff->name}}"><img src="{{$staff->avatar}}" width="36" height="36" alt="{{$staff->name}}" style="border-radius: 50% !important;"></a>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')
        {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
{{--         {{Html::script('bsbmd/plugins/raphael/raphael.min.js')}}
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