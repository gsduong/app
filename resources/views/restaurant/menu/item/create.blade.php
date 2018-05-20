@extends('index')

@section('title')
	Create new item for {{$category->name}}
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
                    <li class="active">Create new item</li>
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
                        <small>Create items for {{$category->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('item.create', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="price">Price (VNĐ)</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="price" class="form-control" required placeholder="Price">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="unit">Unit</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="unit" class="form-control" placeholder="Optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="item_url">Item Url</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="item_url" class="form-control" placeholder="Link to your website or facebook post">
                                        </div>
                                    </div>
                                </div>
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
                                            <label>No<input type="checkbox" name="ship"><span class="lever"></span>Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <label for="description">Description</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="2" name="description" class="form-control no-resize" placeholder="Optional"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Create</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection