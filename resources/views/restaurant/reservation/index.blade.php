@extends('index')

@section('title')
  Reservation for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Reservation</li>
                </ol>
            </div>
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
                    @if($restaurant->reservations->count() > 0)
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
                                <th style="text-align: center;">Created by</th>
                                <th style="text-align: center;">Last Edited by</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($restaurant->reservations as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->date}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->time}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->customer_name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->customer_phone}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->adult}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->children}}</td>
                                <td style="text-align: center; vertical-align: middle;"><span class="{{$item->getLabelClass()}}">{{$item->status}}</span></td>
{{--                                 @if($item->image_url)
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="image">
                                        <a href="{{$item->image_url}}" data-lightbox="image-{{$no + 1}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" width="36" height="36" alt="{{$item->name}}" style="border-radius: 50% !important;"></a>
                                    </div>
                                </td>
                                @else
                                <td style="text-align: center; vertical-align: middle;">N/A</td>
                                @endif --}}
                                <td style="text-align: center; vertical-align: middle;">{{$item->getCreatorName()}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->getLastEditorName()}}</td>
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
    </div>
</div>

@endsection

@section('extra-script')

@endsection