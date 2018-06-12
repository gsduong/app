@extends('index')

@section('title')
  Bot management for {{$restaurant->name}}
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
                    <li class="active">Bot</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Bot Management
                        <small>Easily manage your bot</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">more_vert</i>
                            </a>
                            @if($bot)
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{route('bot.delete', $restaurant->slug)}}" onclick="displayLoadingCircle();" class=" waves-effect waves-block">Delete bot</a></li>
{{--                                 <li><a href="{{route('bot.test', $restaurant->slug)}}" onclick="displayLoadingCircle();" class=" waves-effect waves-block">Test bot</a></li> --}}
                            </ul>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if(!$bot)
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
                            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                                <ul class="list-group">
                                    <div class="row clearfix" style="margin-left: 0; margin-right: 0;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <a href="{{route('bot.create', $restaurant->slug)}}" onclick="displayLoadingCircle();" title="Create bot for this restaurant" style="text-decoration: none;">
                                                <div class="card transparent_class" style="margin-bottom: 10px; border: 5px #D3D3D3 dashed; border-radius: 5px; text-align: center;">
                                                    <p class="vertical-align-custom">Create chatbot for your restaurant page</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
                        </div>
                    @else
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation" class="active"><a href="#general" data-toggle="tab" aria-expanded="true">GENERAL</a></li>
                            <li role="presentation" class=""><a href="#menu" data-toggle="tab" aria-expanded="false">MENU</a></li>
                            <li role="presentation" class=""><a href="#booking" data-toggle="tab" aria-expanded="false">BOOKING</a></li>
                            <li role="presentation"><a href="#order" data-toggle="tab">FOOD ORDER</a></li>
                            <li role="presentation"><a href="#address" data-toggle="tab">ADDRESS</a></li>
                            <li role="presentation"><a href="#phone" data-toggle="tab">PHONE</a></li>
                            <li role="presentation"><a href="#hours" data-toggle="tab">OPENING HOURS</a></li>
                            <li role="presentation"><a href="#chat" data-toggle="tab">DIRECT CHAT</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="general">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="general-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="activate">Activate Bot</label>
                                        <div class="form-group">
                                            <select name="activate">
                                              <option value="1" {{$bot->active ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->active ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="menu">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="menu-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="activate_menu">Activate</label>
                                        <div class="form-group">
                                            <select name="activate_menu">
                                              <option value="1" {{$bot->menu ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->menu ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_menu">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_menu" class="form-control" required value="{{$bot->msg_menu}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="booking">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="booking-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="activate_booking">Activate</label>
                                        <div class="form-group">
                                            <select name="activate_booking">
                                              <option value="1" {{$bot->booking ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->booking ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_booking">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_booking" class="form-control" required value="{{$bot->msg_booking}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="order">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="order-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="activate_order">Activate</label>
                                        <div class="form-group">
                                            <select name="activate_order">
                                              <option value="1" {{$bot->order ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->order ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_order">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_order" class="form-control" required value="{{$bot->msg_order}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="chat">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="chat-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="activate_chat_with_staff">Activate</label>
                                        <div class="form-group">
                                            <select name="activate_chat_with_staff">
                                              <option value="1" {{$bot->chat_with_staff ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->chat_with_staff ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_menu">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_chat_with_staff" class="form-control" required value="{{$bot->msg_chat_with_staff}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="address">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="address-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="address">Activate</label>
                                        <div class="form-group">
                                            <select name="address">
                                              <option value="1" {{$bot->address ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->address ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_menu">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_address" class="form-control" required value="{{$bot->msg_address}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="phone">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="phone-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="phone">Activate</label>
                                        <div class="form-group">
                                            <select name="phone">
                                              <option value="1" {{$bot->phone_number ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->phone_number ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_menu">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_phone_number" class="form-control" required value="{{$bot->msg_phone_number}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="hours">
                            <form method="POST" enctype="multipart/form-data" action="{{route('bot.update', $restaurant->slug)}}" id="hours-form">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="opening_hour">Activate</label>
                                        <div class="form-group">
                                            <select name="opening_hour">
                                              <option value="1" {{$bot->opening_hour ? 'selected' : ''}}>Active</option>
                                              <option value="0" {{$bot->opening_hour ? '' : 'selected'}}>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="msg_menu">Default Response</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="msg_opening_hour" class="form-control" required value="{{$bot->msg_opening_hour}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Save</button>
                            </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
    function displayLoadingCircle(){
        $('section').hide();
        $('.lds-dual-ring').show();
        return true;
    }
</script>
@section('extra-script')
@endsection