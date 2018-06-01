@extends('index')

@section('title')
  Discounts for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Discounts</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            {{$discounts->appends(['branch_id' => Input::get('branch_id')])->links()}}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                        <h2>
                            Discounts
                            <small>Easily manage your restaurant's discounts</small>
                        </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('discount.show-form-create' , $restaurant->slug)}}" title="Add new discount">
                                <i class="material-icons">add</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if($discounts->count() > 0)
                    <table class="table">
                        <thead>
                            <tr >
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Name</th>
                                <th style="text-align: center;">Branch</th>
                                <th style="text-align: center;">Type</th>
                                <th style="text-align: center;">Discount/Bonus Items</th>
                                <th style="text-align: center;">Description</th>
                                <th style="text-align: center;">Last Edit</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $no => $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$no + 1}}</th>
                                <td style="text-align: center; vertical-align: middle;">{{$item->name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->branch->name}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->get_type()}}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($item->discount_percent)
                                    {{$item->discount_percent}} %
                                    @elseif($item->bonus_items)
                                    Bonus Items
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->description}}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="image">
                                        <img src="{{$item->last_editor->avatar}}" width="36" height="36" title="{{$item->last_editor->name}}" alt="{{$item->last_editor->name}}" style="border-radius: 50% !important;" id="image-{{$item->id}}">
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{route('discount.show-form-edit', ['restaurant_slug' => $restaurant->slug, 'description_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    &nbsp;
                                    <a href="{{route('discount.delete', ['restaurant_slug' => $restaurant->slug, 'discount_id' => $item->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    No discount found
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{route('discount.index', ['restaurant_slug' => $restaurant->slug])}}">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12" style="text-align: center;">
                        <div class="input-group" style="margin-bottom: 0;">
                            <select name="branch_id" required class="form-line no-border-bottom" style="height: 32px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); border: 1px !important; border-radius: 15px; margin-bottom: 5px; text-align: center;">
                                <option value="" disabled selected>Branch</option>
                                @foreach($restaurant->contacts as $branch)
                                    <option value="{{$branch->id}}" {{Input::get('branch_id') == $branch->id ? 'selected' : ''}}>{{$branch->name}} - {{$branch->address}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-4 col-xs-12" style="text-align: center;">
                        <button type="submit" class="btn btn-default waves-effect" style="border-radius: 10px;">Filter</button>
                        &nbsp;
                        <a href="{{route('discount.index', $restaurant->slug)}}" class="btn btn-default waves-effect" style="border-radius: 10px;">Clear</a>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

@endsection