@extends('customer.index')

@section('title')
    {{$restaurant->name}} - Order
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
                    <form method="POST" id="form" action="{{route('customer.show-order-cart', ['restaurant_slug' => $restaurant->slug, 'customer_psid' => $customer->app_scoped_id])}}">
                        @csrf
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        @foreach($restaurant->categories as $no => $category)
                            @if($category->items->count() > 0)
                            <li role="presentation" class="{{$no == 0 ? 'active' : ''}}"><a href="#{{$category->slug}}" data-toggle="tab" aria-expanded="true">{{strtoupper($category->name)}} ({{$category->items->where('ship', 1)->count()}})</a></li>
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
                                    @if($item->ship)
                                    <div class="col-sm-3 col-md-2">
                                        <div class="thumbnail">
                                            <div class="image">
                                                <a href="{{$item->image_url}}" data-lightbox="image-{{$item->id}}" data-title="{{$item->name}}"><img src="{{$item->image_url}}" alt="{{$item->name}}"></a>
                                            </div>
                                            {{-- <img src="{{$item->image_url}}"> --}}
                                            <div class="caption">
                                                <h4>{{$item->name}}</h4>
                                                <p><span class="label label-success">{{$item->money()}} đ</span>
                                                    @if($item->ship)
                                                        <i class="material-icons col-red pull-right" title="Nhận ship">local_shipping</i>
                                                    @endif
                                                </p>
                                                <p>
                                                    {{$item->description}}
                                                </p>
                                                @if($item->item_url)
                                                <p><small><a href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a></small></p>
                                                @endif
                                                {{-- <label for="name">Quantity</label> --}}
                                                <div class="row">
                                                    <div class="col-xs-12" style="margin-bottom: 0;">
                                                        <div class="input-group">
                                                            <input type="hidden" name="items[]" value="{{$item->id}}">
                                                            <span class="input-group-btn">
                                                                <button type="button" class="quantity-btn" data-type="minus" data-field="quantity[{{$item->id}}]" data-price={{$item->price}}>
                                                                    <i class="material-icons">remove</i>
                                                                </button>
                                                            </span>
                                                            <input readonly name="quantity[{{$item->id}}]" class="form-control input-number" type="text" style="border: 1px solid #D3D3D3; border-left: none; border-right: none; height: 29px; text-align: center;" value="0">
                                                            <span class="input-group-btn">
                                                                <button type="button" class="quantity-btn" data-type="plus" data-field="quantity[{{$item->id}}]" data-price={{$item->price}}>
                                                                    <i class="material-icons">add</i>
                                                                </button>
                                                            </span> 
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
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
                    </form>
                    @else
                        <div class="row">
                            <div class="col-xs-12" style="text-align: center;">
                                No category found
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <button onclick="topFunction()" id="topBtn" title="Go to top" style="margin-left: 40px;"><i class="material-icons">arrow_upward</i></button>
            <button onclick="resetForm()" id="clearBtn" title="Clear all"><i class="material-icons">clear</i></button>
            <button type="button" onclick="submitForm()" id="orderBtn" title="Submit"><i class="material-icons">shopping_cart</i></button>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
$(".quantity-btn").on("click", function() {

  var $button = $(this);
  var oldValue = $button.parent().parent().find("input.input-number").val();

  if ($button.attr('data-type') == "plus") {
      var newVal = (parseInt(oldValue) || 0) + 1;

    } else {
   // Don't allow decrementing below zero
    if (parseInt(oldValue) > 0) {
      var newVal = (parseInt(oldValue) || 1) - 1;
    } else {
      newVal = 0;
    }
  }
  if (newVal == 0) {
    $(this).parent().parent().find("button.quantity-btn[data-type=minus]").attr('disabled', 'disabled');
  } else {
    $(this).parent().parent().find("button.quantity-btn[data-type=minus]").removeAttr('disabled');
  }
  $button.parent().parent().find("input.input-number").val(newVal);

});
</script>
<script>
    function resetForm() {
        var form = document.getElementById("form");
        if (form) {
            form.reset();
        }
    }
    function submitForm() {
        var form = document.getElementById("form");
        if (form) {
            console.log(form);
            form.submit();
        }
    }
    function updateSubmitBtn(){
        var form = document.getElementById("form");
    }
</script>
@endsection