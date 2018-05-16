@extends('index')

@section('title')
  Contact Information for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Contact Information</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Contact Information
                        <small>You can add upto 5 records</small>
                    </h2>
                </div>
                <div class="body">
                        @if($restaurant->contacts->count() > 0)

                        @foreach($restaurant->contacts as $no => $contact)
                        <form method="POST" action="{{route('contact.update', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <label for="address[{{$no}}]">Address</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="address[{{$no}}]" class="form-control" required placeholder="Address" value="{{$contact->address}}" name="address[{{$no}}]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="phone[{{$no}}]">Phone Number</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="phone[{{$no}}]" class="form-control" required placeholder="Phone number" value="{{$contact->phone}}" name="phone[{{$no}}]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="secondary_phone[{{$no}}]">Second Phone Number</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="secondary_phone[{{$no}}]" class="form-control" placeholder="Optional" value="{{$contact->secondary_phone}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="map_url[{{$no}}]">Map URL</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="map_url[{{$no}}]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label>Actions</label>
                                    <div><a class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number">
                                        <i class="material-icons">delete</i>
                                    </a></div>
                                    <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number" onClick="this.form.submit(); this.disabled=true;">
                                        <i class="material-icons">save</i>
                                    </button></div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @endif
                        <form method="POST" action="{{route('contact.create', $restaurant->slug)}}">
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <label for="address">Address</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="address" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="phone">Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="phone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="secondary_phone">Second Phone Number</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="secondary_phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="map_url">Map URL</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="map_url">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label>Actions</label>
                                    <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number">
                                        <i class="material-icons">add</i>
                                    </button></div>
                                    <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number">
                                        <i class="material-icons">add</i>
                                    </button></div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')
        {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
@endsection