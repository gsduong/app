@extends('index')

@section('title')
  Quan hệ khách hàng
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Trang chủ</a></li>
                    <li><a href="{{route('restaurant.index')}}">Nhà hàng của tôi</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Chăm sóc quan hệ khách hàng</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            {{$customers->appends(['first_name' => Input::get('first_name'), 'last_name' => Input::get('last_name')])->links()}}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Chăm sóc quan hệ khách hàng
                        <small>Danh sách khách hàng đã gửi tin nhắn qua inbox của Facebook Page</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{route('customer.export' , $restaurant->slug)}}" class=" waves-effect waves-block">Export to Excel</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($customers->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Tên khách hàng</th>
                                <th style="text-align: center;">SĐT</th>
                                <th style="text-align: center;">Email</th>
                                {{-- <th style="text-align: center;">Avatar</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $no => $item)
                            <tr>
                                <th style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->getName()}}
                                </td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->phone}}
                                </td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->email}}
                                </td>
{{--                                 <td style="text-align: center; vertical-align: middle;">
                                    @if($item->profile_pic)
                                    <div class="image">
                                        <img src="{{$item->profile_pic}}" width="36" height="36" title="{{$item->first_name}} {{$item->last_name}}" alt="{{$item->first_name}} {{$item->last_name}}" style="border-radius: 50% !important;" id="image-{{$item->id}}">
                                    </div>
                                    @else
                                    N/A
                                    @endif
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    Empty
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{route('customer.index', ['restaurant_slug' => $restaurant->slug])}}">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="first_name" value="{{Input::get('first_name')}}" placeholder="First name" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="last_name" placeholder="Last name" value="{{Input::get('last_name')}}" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Lọc</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><a href="{{route('customer.index', $restaurant->slug)}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Xoá lọc</a>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

@endsection