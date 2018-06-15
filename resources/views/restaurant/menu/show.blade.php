@extends('index')

@section('title')
	{{$category->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Trang chủ</a></li>
                    <li><a href="{{route('restaurant.index')}}">Nhà hàng của tôi</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li><a href="{{route('category.index', $restaurant->slug)}}">Menu</a></li>
                    <li class="active">{{$category->name}}</li>
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
                        <small>Items in {{$category->name}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('item.show-form-create', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" title="Add new item">
                                <i class="material-icons">add</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($items->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Item</th>
                                <th style="text-align: center;">Price</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">URL</th>
                                <th style="text-align: center;">Image</th>
                                <th style="text-align: center;">Accept order</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->money()}} VNĐ</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->unit}}</td>
                                @if($item->item_url)
                                <td style="text-align: center; vertical-align: middle;"><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></td>
                                @else
                                <td style="text-align: center; vertical-align: middle;">N/A</td>
                                @endif
                                @if($item->image_url)
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="image">
                                        <a href="{{$item->image_url}}" data-lightbox="image-{{$no + 1}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" width="36" height="36" alt="{{$item->name}}" style="border-radius: 50% !important;"></a>
                                    </div>
                                </td>
                                @else
                                <td style="text-align: center; vertical-align: middle;">N/A</td>
                                @endif
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="switch">
                                        <label>No<input type="checkbox" name="ship" {{ $item->ship ? 'checked' : ''}} disabled><span class="lever"></span>Yes</label>
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{route('item.delete', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('item.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    &nbsp;
                                    @if($item->image_url)
                                    <a href="{{route('item.delete-image', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Are you sure you want to delete this image?');" title="Delete Image">
                                        <i class="material-icons">broken_image</i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    Trống
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{route('category.show', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="form-line no-border-bottom" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);border: 1px !important; border-radius: 10px;">
                                <input type="text" class="form-control" name="name" value="{{Input::get('name')}}" placeholder=" Name" style="padding-left: 15px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3"><button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Filter</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3"><a href="{{route('category.show', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Clear</a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 pull-right">
                        {{$items->appends(['name' => Input::get('name')])->links()}}
                    </div>
                </div>                
            </form>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection