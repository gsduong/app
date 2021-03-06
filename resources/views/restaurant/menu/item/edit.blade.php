@extends('index')

@section('title')
	Chỉnh sửa thông tin sản phầm - {{$category->name}}
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
                    <li><a href="{{route('category.show', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}">{{$category->name}}</a></li>
                    <li class="active">Chỉnh sửa</li>
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
                        <small>Chỉnh sửa sản phẩm - {{$category->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('item.update', ['restaurant_slug' => $restaurant->slug, 'category_slug' => $category->slug])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="name">Tên</label>
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
                                    <label for="price">Giá (VNĐ)</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="price" class="form-control" required placeholder="Price" value="{{isset($item) ? $item->price : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="unit">Đơn vị</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="unit" class="form-control" placeholder="Optional" value="{{isset($item) ? $item->unit : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="item_url">Đường dẫn</label>
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
                                    <label>Image</label>
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
                                    <label for="ship">Chấp nhận order online</label>
                                    <div class="form-group">
                                        <div class="switch">
                                            <label>No<input type="checkbox" name="ship" {{ $item->ship ? 'checked' : ''}}><span class="lever"></span>Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                                    <label for="description">Chi tiết</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="2" name="description" class="form-control no-resize" placeholder="Optional">{{$item->description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Lưu</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')

@endsection