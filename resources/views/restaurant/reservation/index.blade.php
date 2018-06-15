@extends('index')

@section('title')
  Đơn đặt bàn của {{$restaurant->name}}
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
                    <li class="active">Đơn đặt bàn</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            {{$reservations->appends(['date' => Input::get('date'), 'name' => Input::get('name'), 'phone' => Input::get('phone'), 'status' => Input::get('status')])->links()}}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                        <h2>
                            Đơn đặt bàn
                            <small>Quản lý đơn đặt bàn cho nhà hàng của bạn</small>
                        </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('reservation.show-form-create' , $restaurant->slug)}}" title="Add new reservation">
                                <!-- class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" -->
                                <i class="material-icons">add</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($reservations->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Ngày</th>
                                <th style="text-align: center;">Thời gian</th>
                                <th style="text-align: center;">Tên</th>
                                <th style="text-align: center;">SĐT</th>
                                <th style="text-align: center;">Số người lớn</th>
                                <th style="text-align: center;">Số trẻ em</th>
                                <th style="text-align: center;">Trạng thái</th>
                                <th style="text-align: center;">Ghi chú</th>
                                <th style="text-align: center;">Tạo bởi</th>
                                <th style="text-align: center;">Lần cuối chỉnh sửa bởi</th>
                                <th style="text-align: center;">Tuỳ chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;" id="date-{{$item->id}}">{{$item->date}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="time-{{$item->id}}">{{date('H:i', strtotime($item->time))}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="name-{{$item->id}}">{{$item->customer_name}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="phone-{{$item->id}}">{{$item->customer_phone}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="adult-{{$item->id}}">{{$item->adult}}</td>
                                <td style="text-align: center; vertical-align: middle;" id="children-{{$item->id}}">{{$item->children}}</td>
                                <td style="text-align: center; vertical-align: middle;"><span class="{{$item->getLabelClass()}}" id="status-{{$item->id}}">{{$item->status}}</span></td>
                                @if($item->customer_requirement)
                                <td style="text-align: center; vertical-align: middle;" id="requirement-{{$item->id}}">
                                    <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="{{asset('note-md.png')}}" data-lightbox="image-{{$item->id}}" data-title="{{$item->customer_requirement}}"><i class="material-icons">event_note</i></a>
                                </td>
                                @else
                                <td style="text-align: center; vertical-align: middle;" id="requirement-{{$item->id}}">Không có</td>
                                @endif
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($item->created_by_bot)
                                    <div class="image">
                                        <img src="{{asset('bot-icon.png')}}" width="36" height="36" alt="Bot" title="Bot" style="border-radius: 50% !important;">
                                    </div>
                                    @else
                                    <div class="image">
                                        <img src="{{$item->creator()->avatar}}" width="36" height="36" alt="{{$item->creator()->name}}" title="{{$item->creator()->name}}" style="border-radius: 50% !important;">
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;"  id="{{$item->id}}">
                                    @if($item->last_editor())
                                    <div class="image">
                                        <img src="{{$item->last_editor()->avatar}}" width="36" height="36" title="{{$item->last_editor()->name}}" alt="{{$item->last_editor()->name}}" style="border-radius: 50% !important;" id="image-{{$item->id}}">
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{route('reservation.delete', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Bạn muốn xoá đơn đặt bàn này?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('reservation.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    Không có đơn đặt bàn nào
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{route('reservation.index', ['restaurant_slug' => $restaurant->slug])}}">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="date" class="form-control" name="date" value="{{isset($today) ? $today : Input::get('date')}}" style="padding-left: 5px; border-radius: 10px;" placeholder="Ngày">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <select name="status" class="form-line no-border-bottom" style="height: 35px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); border: 1px !important; border-radius: 15px; margin-bottom: 5px;">
                                <option value="" disabled selected>Status</option>
                                <option value="pending" {{Input::get('status') == 'pending' ? 'selected' : ''}}>Chờ duyệt</option>
                                <option value="confirmed" {{Input::get('status') == 'confirmed' ? 'selected' : ''}}>Đặt bàn thành công</option>
                                <option value="canceled" {{Input::get('status') == 'canceled' ? 'selected' : ''}}>Đã huỷ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="name" value="{{Input::get('name')}}" placeholder="Tên" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="phone" placeholder="SĐT" value="{{Input::get('phone')}}" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Lọc</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><a href="{{route('reservation.index', $restaurant->slug)}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Xoá lọc</a>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

@endsection