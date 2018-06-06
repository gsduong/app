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
                        <small><a href="{{route('customer.show-form-create-order', ['restaurant_slug' => $restaurant->slug, 'customer_psid' => $customer->app_scoped_id])}}">Back to Menu</a></small>
                    </h2>
                </div>
                <div class="body">
                    @if($items->count())
                    <!-- Nav tabs -->
                    <form method="POST" id="form" action="{{route('customer.create-order', ['restaurant_slug' => $restaurant->slug, 'customer_psid' => $customer->app_scoped_id])}}">
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
                                    <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <b>Name</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">person</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" name="name" id="name" onfocusout="updateLabel(this)" class="form-control" placeholder="Please provide your name" required="true" value="{{ $customer->getName() ? $customer->getName() : old('name') }}">
                                                            </div>
                                                        </div>
                                                        <label id="name-error" class="validation-error-label" for="name"><small>{{ $errors->first('name') }}</small></label>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Phone</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">phone</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" name="phone" id="phone" onfocusout="updateLabel(this)" class="form-control" placeholder="Please provide your phone number" required="true" value="{{ $customer->phone ? $customer->phone : old('phone') }}">
                                                            </div>
                                                        </div>
                                                        <label id="phone-error" class="validation-error-label" for="phone"><small>{{ $errors->first('phone') }}</small></label>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Address</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">location_on</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" onfocusout="updateLabel(this)" required class="form-control" name="address" placeholder="Please provide your address" id="address" value="{{ $customer->address ? $customer->address : old('address') }}">
                                                            </div>
                                                        </div>
                                                        <label id="address-error" class="validation-error-label" for="address"><small>{{ $errors->first('address') }}</small></label>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <b>Email</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">email</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="email" onfocusout="updateLabel(this)" class="form-control" name="email" placeholder="Optional" id="email" value="{{ $customer->email ? $customer->email : old('email') }}">
                                                            </div>
                                                        </div>
                                                        <label id="email-error" class="validation-error-label" for="email"><small>{{ $errors->first('email') }}</small></label>
                                                    </div>
                                                    @if($restaurant->contacts->count() > 0)
                                                        <div class="col-xs-12">
                                                            <b>Branch</b>
                                                            <div class="input-group" style="margin-bottom: 0;">
                                                                <div class="demo-radio-button">
                                                                    <div class="row">
                                                                    @foreach($restaurant->contacts as $no => $contact)
                                                                    <div class="col-xs-6" style="padding-left: 11px; margin-bottom: 5px;">
                                                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'checked' : ''}} value="{{$contact->id}}">
                                                                        <label for="address_{{$no + 1}}">{{$contact->address}}</label>
                                                                    </div>

                                                                    @endforeach
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-xs-12">
                                                        <b>Note</b>
                                                        <div class="input-group" style="margin-bottom: 0;">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">event_note</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" class="form-control" name="requirement" placeholder="Optional" value="{{ old('requirement') }}">
                                                            </div>
                                                        </div>
                                                       {{--  <label id="email-error" class="validation-error-label" for="email"><small>{{ $errors->first('email') }}</small></label> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            <button type="button" onclick="formValidate()" id="orderBtn" title="Submit" style="margin-left: -75px;"><i class="material-icons">check</i></button>
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
<script>
    function formValidate(){
        $flag = true;
        var form = document.getElementById("form");
        var name = document.getElementById("name");
        if (!name.checkValidity()) {
            document.getElementById("name-error").style.display = "block";
            document.getElementById("name-error").innerHTML = name.validationMessage;
            $flag = false;
        } else {
            document.getElementById("name-error").style.display = "none";
        }
        var phone = document.getElementById("phone");
        if (!phone.checkValidity()) {
            document.getElementById("phone-error").style.display = "block";
            document.getElementById("phone-error").innerHTML = phone.validationMessage;
            $flag = false;
        } else {
            document.getElementById("phone-error").style.display = "none";
        }
        var email = document.getElementById("email");
        if (!email.checkValidity()) {
            document.getElementById("email-error").style.display = "block";
            document.getElementById("email-error").innerHTML = email.validationMessage;
            $flag = false;
        } else {
            document.getElementById("email-error").style.display = "none";
        }
        var address = document.getElementById("address");
        if (!address.checkValidity()) {
            document.getElementById("address-error").style.display = "block";
            document.getElementById("address-error").innerHTML = address.validationMessage;
            $flag = false;
        } else {
            document.getElementById("address-error").style.display = "none";
        }
        if ($flag) {
            form.submit();
        }
        return $flag;
    }
    function updateLabel(selectedInput) {
        if (selectedInput.value) {
            selectedInput.parentElement.parentElement.nextElementSibling.style.display = "none";
        }
    }
</script>
@endsection