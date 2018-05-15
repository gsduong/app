@extends('auth.app')

@section('content')
<div class="row clearfix demo-button-sizes">
    <div class="col-xs-2">
		<p>&nbsp;</p>
    </div>
    <div class="col-xs-8">
        <a href="{{isset($login_url) ? $login_url : ''}}" type="button" class="btn btn-block btn-lg waves-effect social facebook waves-light" style="min-height: 30px;"><i class="fa fa-facebook"></i>Sign in using Facebook</a>
    </div>
    <div class="col-xs-2">
		<p>&nbsp;</p>
    </div>
</div>
@endsection
