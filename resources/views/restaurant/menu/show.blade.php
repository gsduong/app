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
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->items as $no => $item)
                            <tr>
                                <th scope="row">{{$no + 1}}</th>
                                <td>{{$item->name}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->unit}}</td>
                                @if($item->item_url)
                                <td><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></td>
                                @else
                                <td>N/A</td>
                                @endif
                                <td><a href="{{route('item.delete', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}">Delete</a>&nbsp; <a href="{{route('item.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug, 'item_id' => $item->id])}}">Edit</a></td>
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