@extends('info.layout')

@section('title', 'SHOPPING CART')

@section('message', 'Please pick at least one item')

@section('content')
@if(isset($restaurant))
	<div class="col-xs-3"></div>
	<div class="col-xs-6">
		<a class="btn btn-block btn-lg btn-default waves-effect" href="{{route('customer.show-form-create-order', $restaurant->slug)}}">Back to Menu</a>
	</div>
	<div class="col-xs-3"></div>
@endif
@endsection