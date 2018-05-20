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
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
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
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{route('item.show-form-create', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" class=" waves-effect waves-block">Add item</a></li>
                                <li><a href="javascript:void(0);" class=" waves-effect waves-block">View as thumbnails</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($category->items->count() > 0)
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
                            @foreach($category->items as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->price}} VNĐ</td>
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
                    No item found
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection