@extends('index')

@section('title')
	Edit reservation for {{$restaurant->name}}
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
                    <li><a href="{{route('reservation.index', $restaurant->slug)}}">Reservations</a></li>
                    <li class="active">Edit reservation</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{$restaurant->name}}
                        <small>Edit reservation for {{$restaurant->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('reservation.update', ['restaurant_slug' => $restaurant->slug])}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Name" value={{$reservation->customer_name}}>
                                            <input type="hidden" name="id" required value={{$reservation->id}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="price">Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="phone" class="form-control" required placeholder="Phone" value={{$reservation->customer_phone}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="date">Date</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="date" class="form-control" required value={{$reservation->date}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="time">Time</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="time" name="time" class="form-control" required value={{$reservation->time}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="adult">Number of adults</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" required name="adult" class="form-control" min="1" value={{$reservation->adult}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="children">Number of children</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="children" class="form-control" min="0" value={{$reservation->children}}>
                                        </div>
                                    </div>
                                </div>
                                @if($restaurant->contacts->count() > 0)
                                    <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                        <h2 class="card-inside-title">Address</h2>
                                        <div class="demo-radio-button">
                                        @foreach($restaurant->contacts as $no => $contact)
                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'required' : ''}} value="{{$contact->id}}" {{$reservation->address_id == $contact->id ? 'checked' : ''}}>
                                        <label for="address_{{$no + 1}}"> Cơ sở {{$no + 1}} - {{$contact->address}}</label>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <h2 class="card-inside-title">Status</h2>
                                    <div class="demo-radio-button">
                                    <input name="status" type="radio" id="status_1" value="pending" {{$reservation->status == 'pending' ? 'checked' : ''}}>
                                    <label for="status_1">Pending</label>
                                    <input name="status" type="radio" id="status_2" value="confirmed" {{$reservation->status == 'confirmed' ? 'checked' : ''}}>
                                    <label for="status_2">Confirmed</label>
                                    <input name="status" type="radio" id="status_3" value="cancelled" {{$reservation->status == 'cancelled' ? 'checked' : ''}}>
                                    <label for="status_3">Cancelled</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <label for="customer_requirement">Other requirement</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="2" name="customer_requirement" class="form-control no-resize" placeholder="Optional">{{$reservation->customer_requirement}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Update</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection