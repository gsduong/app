@extends('index')

@section('title')
  Menu management for {{$restaurant->name}}
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
                    <li class="active">Menu</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Menu Management
                        <small>Easily create and manage your menu</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('category.list', $restaurant->slug)}}" title="Edit Menu">
                                <i class="material-icons">edit</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if($restaurant->categories->count())
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        @foreach($restaurant->categories as $no => $category)
                        <li role="presentation" class="{{$no == 0 ? 'active' : ''}}"><a href="#{{$category->slug}}" data-toggle="tab" aria-expanded="true">{{strtoupper($category->name)}} ({{$category->items->count()}})</a></li>
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($restaurant->categories as $no => $category)
                        <div role="tabpanel" class="tab-pane fade {{$no == 0 ? 'active' : ''}} in" id="{{$category->slug}}">
                            <div class="row">
                                {{-- <div class="col-lg-11 col-md-11 col-sm-10 col-xs-12"></div> --}}
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right" style="padding: 0; text-align: center; margin-bottom: 15px;">
                                    <a class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="{{route('item.show-form-create' , ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" title="Add new item for {{$category->name}}">
                                        <!-- class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" -->
                                        <i class="material-icons">add</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('category.delete', ['slug' => $restaurant->slug, 'category_id' => $category->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </div>
                            </div>
                            @if($category->items->count())
                                <div class="row">
                                @foreach($category->items as $idx => $item)
                                    <div class="col-sm-6 col-md-3">
                                        <div class="thumbnail">
                                            <img src="{{$item->image_url}}">
                                            <div class="caption">
                                                <h4>{{$item->name}}</h4>
                                                <p><span class="label label-success">{{$item->money()}} đ</span></p>
                                                <p>
                                                    {{$item->description}}
                                                </p>
                                                @if($item->item_url)
                                                <p><small><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></small></p>
                                                @endif
                                                <p>
                                                    <a href="{{route('item.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}" class="btn btn-default waves-effect" role="button">EDIT</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            @else
                            <div class="row">
                                <div class="col-xs-12" style="text-align: center;">
                                    No item found
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                        <div class="row">
                            <div class="col-xs-12" style="text-align: center;">
                                No category found
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')
        {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
@endsection