@extends('index')

@section('title')
	Edit item in {{$category->name}}
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
                    <li><a href="{{route('category.index', $restaurant->slug)}}">Menu</a></li>
                    <li><a href="{{route('category.show', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}">{{$category->name}}</a></li>
                    <li class="active">Edit item</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{$category->name}}
                        <small>Edit item in {{$category->name}} category</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('item.update', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Name" value="{{isset($item) ? $item->name : ''}}">
                                        </div>
                                    </div>
                                </div>
                                @if(isset($item))
                                    <input type="hidden" name="item_id" class="form-control" required value="{{$item['id']}}">
                                @endif
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="price">Price (VNĐ)</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="price" class="form-control" required placeholder="Price" value="{{isset($item) ? $item->price : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="unit">Unit</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="unit" class="form-control" placeholder="Optional" value="{{isset($item) ? $item->unit : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="item_url">Item Url</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="item_url" class="form-control" placeholder="Optional" value="{{isset($item) ? $item->item_url : ''}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                @if($item->image_url)
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label>Current Image</label>
                                    <div class="form-group">
                                        <div class="image">
                                            <a href="{{$item->image_url}}" data-lightbox="image-1" data-title="{{$item->name}}"><img src="{{$item->image_url}}" width="48" height="48" alt="{{$item->name}}" style="border-radius: 50% !important;"></a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="image_file">Image Upload</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="file" name="image_file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="ship">Accept online order for this item</label>
                                    <div class="form-group">
                                        <div class="switch">
                                            <label>No<input type="checkbox" name="ship" {{ $item->ship ? 'checked' : ''}}><span class="lever"></span>Yes</label>
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