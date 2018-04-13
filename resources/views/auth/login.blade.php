@extends('auth.app')

@section('content')
<div class="row clearfix demo-button-sizes">
    <div class="col-xs-2">
		<p>&nbsp;</p>
    </div>
    <div class="col-xs-8">
        <a href="{{route('provider', 'facebook')}}" type="button" class="btn btn-block btn-lg waves-effect social facebook waves-light"><i class="fa fa-facebook"></i>Đăng nhập bằng Facebook</a>
    </div>
    <div class="col-xs-2">
		<p>&nbsp;</p>
    </div>
</div>
@endsection
