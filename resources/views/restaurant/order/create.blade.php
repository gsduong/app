@extends('index')

@section('title')
	Tạo mới đơn hàng order giúp khách hàng - {{$restaurant->name}}
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
                    <li><a href="{{route('reservation.index', $restaurant->slug)}}">Đơn hàng order</a></li>
                    <li class="active">Tạo mới</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{$restaurant->name}}
                        <small>Tạo mới đơn hàng order - {{$restaurant->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('order.create', ['restaurant_slug' => $restaurant->slug])}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-3 col-lg-3 col-xs-3 col-sm-3">
                                    <label for="customer_name">Customer name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="customer_name" class="form-control" required placeholder="Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-3 col-sm-3">
                                    <label for="customer_phone">Customer phone</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="customer_phone" class="form-control" required placeholder="Phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-3 col-sm-3">
                                    <label for="customer_address">Customer addresss</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" required name="customer_address" class="form-control" placeholder="Address">
                                        </div>
                                    </div>
                                </div>
                                @if($restaurant->contacts->count() > 0)
                                    <div class="col-md-3 col-lg-3 col-xs-3 col-sm-3">
                                        <h2 class="card-inside-title">Branch</h2>
                                        <div class="demo-radio-button">
                                        @foreach($restaurant->contacts as $no => $contact)
                                        <input name="branch_id" type="radio" id="branch_{{$no + 1}}" {{$no == 0 ? 'checked' : ''}} value="{{$contact->id}}">
                                        <label for="branch_{{$no + 1}}"> Branch {{$no + 1}} - {{$contact->address}}</label>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>
                                                Order
                                                <small>Pick your items</small>
                                            </h2>
                                        </div>
                                        <div class="body table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th style="text-align: center;">Item name</th>
                                                        <th style="text-align: center;">Image</th>
                                                        <th style="text-align: center;">Price</th>
                                                        <th style="text-align: center;">Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($restaurant->categories as $category)
                                                        @foreach($category->items as $item)
                                                        <tr>
                                                            <td style="vertical-align: middle;">{{$category->name}}</td>
                                                            <td style="text-align: center; vertical-align: middle;">{{$item->name}}</td>
                                                            @if($item->image_url)
                                                            <td style="text-align: center; vertical-align: middle;">
                                                                <div class="image">
                                                                    <a href="{{$item->image_url}}" data-lightbox="image-{{$no + 1}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" width="36" height="36" alt="{{$item->name}}" style="border-radius: 50% !important;"></a>
                                                                </div>
                                                            </td>
                                                            @else
                                                            <td style="text-align: center; vertical-align: middle;">N/A</td>
                                                            @endif
                                                            <td style="text-align: center; vertical-align: middle;">{{$item->price}} VND {{'('.$item->unit.')'}}</td>
                                                            <td style="text-align: center; vertical-align: middle;"><input type="number" min="0" name="quantity['{{$item->id}}']" class="form-control"></td>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                    <label for="customer_note">Customer note</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="customer_note" class="form-control" placeholder="Note">
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