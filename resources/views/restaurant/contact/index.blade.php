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
                                    <label for="address">Address</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="hidden" name="id" required value="{{$contact->id}}">
                                            <input type="text" name="address" class="form-control" required placeholder="Address" value="{{$contact->address}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="phone">Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="phone" class="form-control" required placeholder="Phone number" value="{{$contact->phone}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="secondary_phone">#2 Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="secondary_phone" class="form-control" placeholder="Optional" value="{{$contact->secondary_phone}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="map_url">Map URL</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="map_url" value="{{$contact->map_url}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="text-align: center;">
                                    <label>Actions</label>
                                    <div><a href="{{route('contact.delete', ['slug' => $restaurant->slug, 'contact_id' => $contact->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Delete">
                                        <i class="material-icons">delete</i>
                                    </a>&nbsp;<button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" onClick="this.form.submit(); this.disabled=true;" title="Save">
                                        <i class="material-icons">save</i>
                                    </button></div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @endif
                        <form method="POST" action="{{route('contact.create', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <label for="address">Address</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="address" required placeholder="Your restaurant address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="phone">Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="phone" required placeholder="Primary Phone Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label for="secondary_phone">#2 Phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="secondary_phone" placeholder="Optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="map_url">Map URL <a href="https://goo.gl/" target="_blank" title="Get shortened URL">(*)</a></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="map_url" placeholder="Optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="text-align: center;">
                                    <label>Actions</label>
                                    <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number" onClick="this.form.submit(); this.disabled=true;">
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