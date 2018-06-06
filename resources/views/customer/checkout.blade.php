@extends('customer.index')

@section('title')
    {{$restaurant->name}} - Checkout
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header" style="text-align: center;">
                    <h2>
                        <small><a href="{{route('customer.show-form-create-order', $restaurant->slug)}}">Back to Menu</a></small>
                    </h2>
                </div>
                <div class="body">
                    @if($items->count())
                    <!-- Nav tabs -->
                    <form method="POST" id="form" action="{{route('customer.create-order', $restaurant->slug)}}">
                        @csrf

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in">
                            @if($items->count())
                                <div class="row">
                                @foreach($items as $idx => $item)
                                    @if($item->ship)
                                    <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
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
                                                        <div class="input-group" style="margin-bottom: 5px;">
                                                            <input type="hidden" name="items[]" value="{{$item->id}}">
                                                            <span class="input-group-btn">
                                                                <button type="button" class="quantity-btn" data-type="minus" data-field="quantity[{{$item->id}}]" data-price="{{$item->price}}">
                                                                    <i class="material-icons">remove</i>
                                                                </button>
                                                            </span>
                                                            <input readonly name="quantity[{{$item->id}}]" class="form-control input-number" type="text" style="border: 1px solid #D3D3D3; border-left: none; border-right: none; height: 29px; text-align: center;" value="{{$quantity[$item->id]}}">
                                                            <span class="input-group-btn">
                                                                <button type="button" class="quantity-btn" data-type="plus" data-field="quantity[{{$item->id}}]" data-price="{{$item->price}}">
                                                                    <i class="material-icons">add</i>
                                                                </button>
                                                            </span> 
                                                        </div>  
                                                    </div>
                                                </div>
                                                <p>Price: <span class="label label-danger" style="vertical-align: middle;" id="total-{{$item->id}}">Total</span></p>
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
{{--                         @endif
                        @endforeach --}}
                        
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
            <button onclick="topFunction()" id="topBtn" title="Go to top" style="margin-left: 5px;"><i class="material-icons">arrow_upward</i></button>
            <button type="button" onclick="submitForm()" id="orderBtn" title="Submit" style="margin-left: -75px;"><i class="material-icons">check</i></button>
            <span class="label label-danger" id="total-price"></span>
        </div>
    </div>
</div>
@endsection

@section('extra-script')
<script>
$(document).ready(function() {
    var buttons = $("button.quantity-btn[data-type=minus]");
    var totalPrice = 0;
    buttons.each(function() {
        var spanPrice = $(this).parent().parent().parent().parent().next().children();
        var qty = $(this).parent().next().val();
        var price = parseInt($(this).attr('data-price')) * parseInt(qty);
        var spanPrice = $(this).parent().parent().parent().parent().next().children();
        spanPrice.text(price + " đ");
        totalPrice += price;
    });
    $("#total-price").text(totalPrice);
});
$(".quantity-btn").on("click", function() {

  var $button = $(this);
  var spanTotalPrice = $("#total-price");
  var totalPrice = spanTotalPrice.text();
  var oldValue = $button.parent().parent().find("input.input-number").val();
  var spanPrice = $button.parent().parent().parent().parent().next().children();
  if ($button.attr('data-type') == "plus") {
      var newVal = (parseInt(oldValue) || 0) + 1;
      totalPrice = parseInt(totalPrice) + 1 * parseInt($button.attr("data-price"));
    } else {
   // Don't allow decrementing below zero
    if (parseInt(oldValue) > 0) {
      var newVal = (parseInt(oldValue) || 1) - 1;
      totalPrice = parseInt(totalPrice) - 1 * parseInt($button.attr("data-price"));
    } else {
      newVal = 0;
    }
  }
  if (newVal == 0) {
    $(this).parent().parent().find("button.quantity-btn[data-type=minus]").attr('disabled', 'disabled');
  } else {
    $(this).parent().parent().find("button.quantity-btn[data-type=minus]").removeAttr('disabled');
  }
  var itemsPrice = parseInt($button.attr('data-price')) * newVal;
  $button.parent().parent().find("input.input-number").val(newVal);
  spanPrice.text(itemsPrice + " đ");
  spanTotalPrice.text(totalPrice);
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