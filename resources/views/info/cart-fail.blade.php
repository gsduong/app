@extends('info.layout')

@section('title', 'SHOPPING CART')

@section('message', 'Please pick at least one item')

@section('content')
@if(isset($restaurant))
	<div class="col-xs-3"></div>
	<div class="col-xs-6">
		<a class="btn btn-block btn-lg btn-default waves-effect" style="border-radius: 15px;" href="{{route('customer.show-form-create-order', ['restaurant_slug' => $restaurant->slug, 'customer_psid' => $customer->app_scoped_id])}}">Back to Menu</a>
	</div>
	<div class="col-xs-3"></div>
@endif
@endsection