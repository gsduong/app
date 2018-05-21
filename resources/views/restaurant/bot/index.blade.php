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
{{--                     <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="" class=" waves-effect waves-block">View as a list</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>
                <div class="body">
                    @if(!$bot)
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12"></div> 
                            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                                <ul class="list-group">
                                    <div class="row clearfix" style="margin-left: 0; margin-right: 0;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <a href="{{route('bot.create', $restaurant->slug)}}" title="Create bot for this restaurant" style="text-decoration: none;">
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
                    Bot created successfully!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')
@endsection