@extends('index')

@section('title')
  Reservation for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Reservation</li>
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
                            Reservation
                            <small>Easily manage your restaurant's reservation</small>
                        </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{route('reservation.show-form-create' , $restaurant->slug)}}" class=" waves-effect waves-block">Create a reservation for customer</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($reservations->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Date</th>
                                <th style="text-align: center;">Time</th>
                                <th style="text-align: center;">Name</th>
                                <th style="text-align: center;">Phone</th>
                                <th style="text-align: center;"># Adult</th>
                                <th style="text-align: center;"># Children</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Note</th>
                                <th style="text-align: center;">Created by</th>
                                <th style="text-align: center;">Last Edit</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->date}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{date('H:i', strtotime($item->time))}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->customer_name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->customer_phone}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->adult}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->children}}</td>
                                <td style="text-align: center; vertical-align: middle;"><span class="{{$item->getLabelClass()}}">{{$item->status}}</span></td>
                                @if($item->customer_requirement)
                                <td style="text-align: center; vertical-align: middle;">
                                    <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="{{asset('note-md.png')}}" data-lightbox="image-{{$no + 1}}" data-title="{{$item->customer_requirement}}"><i class="material-icons">event_note</i></a>
                                </td>
                                @else
                                <td style="text-align: center; vertical-align: middle;">N/A</td>
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
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($item->last_editor())
                                    <div class="image">
                                        <img src="{{$item->last_editor()->avatar}}" width="36" height="36" title="{{$item->last_editor()->name}}" alt="{{$item->last_editor()->name}}" style="border-radius: 50% !important;">
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{route('reservation.delete', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('reservation.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    &nbsp;
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    No reservation found
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
                                <input type="date" class="form-control" name="date" value="{{isset($today) ? $today : Input::get('date')}}" style="padding-left: 5px; border-radius: 10px;" placeholder="Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <select name="status" class="form-line no-border-bottom" style="height: 35px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); border: 1px !important; border-radius: 15px; margin-bottom: 5px;">
                                <option value="" disabled selected>Status</option>
                                <option value="pending" {{Input::get('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                                <option value="confirmed" {{Input::get('status') == 'confirmed' ? 'selected' : ''}}>Confirmed</option>
                                <option value="canceled" {{Input::get('status') == 'canceled' ? 'selected' : ''}}>Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="name" value="{{Input::get('name')}}" placeholder=" Name" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{Input::get('phone')}}" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Filter</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6"><a href="{{route('reservation.index', $restaurant->slug)}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Clear</a>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

@endsection