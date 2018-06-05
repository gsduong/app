@extends('customer.index')

@section('title')
    {{$restaurant->name}} - Menu
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    @if($restaurant->categories->count())
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        @foreach($restaurant->categories as $no => $category)
                            @if($category->items->count() > 0)
                            <li role="presentation" class="{{$no == 0 ? 'active' : ''}}"><a href="#{{$category->slug}}" data-toggle="tab" aria-expanded="true">{{strtoupper($category->name)}} ({{$category->items->count()}})</a></li>
                            @endif
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        
                        @foreach($restaurant->categories as $no => $category)
                        @if($category->items->count() > 0)
                        <div role="tabpanel" class="tab-pane fade {{$no == 0 ? 'active' : ''}} in" id="{{$category->slug}}">
                            @if($category->items->count())
                                <div class="row">
                                @foreach($category->items as $idx => $item)
                                    <div class="col-sm-3 col-md-2">
                                        <div class="thumbnail">
                                            <div class="image">
                                                <a href="{{$item->image_url}}" data-lightbox="image-{{$item->id}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" alt="{{$item->name}}"></a>
                                            </div>
                                            {{-- <img src="{{$item->image_url}}"> --}}
                                            <div class="caption">
                                                <h4>{{$item->name}}</h4>
                                                <p><span class="label label-success">{{$item->money()}} đ</span></p>
                                                <p>
                                                    {{$item->description}}
                                                </p>
                                                @if($item->item_url)
                                                <p><small><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></small></p>
                                                @endif
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
                        @endif
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
            <button onclick="topFunction()" id="topBtn" title="Go to top" style="margin-right: -35px !important;"><i class="material-icons">arrow_upward</i></button>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
@endsection