@extends('restaurant.master')

@section('title')
	Create new restaurant
@endsection

@section('extra-css')

@endsection

@section('content')
	<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            @if(isset($page))
                            Create restaurant for <a href="{{$page['url']}}" target="_blank"><img src="{{$page['page_profile_picture']}}" width="36" height="36" alt="{{$page['name']}}" style="border-radius: 50% !important;"> {{$page['name']}}</a>
                            @else
                            Create new restaurant
                            @endif
                        </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('restaurant.create')}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Nhập tên nhà hàng của bạn" value="{{isset($page) ? $page['name'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                @if(isset($page))
                                <input type="hidden" name="fb_page_id" class="form-control" required placeholder="Nhập Page ID của bạn" value="{{$page['id']}}">
                                @else
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="fb_page_id">Facebook Page ID</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="fb_page_id" class="form-control" required placeholder="Nhập Page ID của bạn">
                                        </div>
                                    </div>
                                </div>
                                @endif
{{--                                 <div class="col-md-2 col-lg-2 col-xs-12 col-sm-12">
                                    <p>&nbsp;</p>
                                </div> --}}
                            </div>
{{--                             <div class="form-group address-form">
                                <div class="row clearfix">
                                    <div class="col-md-5 col-lg-5 col-xs-12 col-sm-12">
                                        <label for="address[]">Địa chỉ</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="address[]" class="form-control" required placeholder="Nhập địa chỉ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-xs-12 col-sm-12">
                                        <label for="phone[]">Điện thoại</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="phone[]" class="form-control" required placeholder="Nhập số điện thoại">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-xs-12 col-sm-12" style="text-align: center;">
                                        <label>Actions</label>
                                        <div><button type="button" id="add_btn" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new address and phone number">
                                            <i class="material-icons">add</i>
                                        </button></div>
                                    </div>
                                </div>
                            </div> --}}
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect" style="margin-top: 0;">Tạo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
{{--         {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
        {{Html::script('bsbmd/plugins/raphael/raphael.min.js')}}
        {{Html::script('bsbmd/plugins/morrisjs/morris.js')}}
        {{Html::script('bsbmd/plugins/chartjs/Chart.bundle.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.resize.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.pie.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.categories.js')}}
        {{Html::script('bsbmd/plugins/flot-charts/jquery.flot.time.js')}}
        {{Html::script('bsbmd/plugins/jquery-sparkline/jquery.sparkline.js')}}
        {{Html::script('bsbmd/js/pages/index.js')}} --}}
        <script type="text/javascript">
        $(document).ready(function(){
            var x = 1; //Initial field counter is 1
            var maxField = 5; //Input fields increment limitation
            var addButton = $('#add_btn'); //Add button selector
            var wrapper = $('.address-form'); //Input field wrapper
            var fieldHTML = '<div class="row clearfix"><div class="col-md-5 col-lg-5 col-xs-12 col-sm-12"><label for="address[]">Địa chỉ cơ sở khác</label><div class="form-group"><div class="form-line"><input type="text" name="address[]" class="form-control" required placeholder="Nhập địa chỉ" style="z-index: 0;"></div></div></div><div class="col-md-5 col-lg-5 col-xs-12 col-sm-12"><label for="phone[]">Điện thoại</label><div class="form-group"><div class="form-line"><input type="text" name="phone[]" class="form-control" required placeholder="Nhập số điện thoại"></div></div></div><div class="col-md-2 col-lg-2 col-xs-12 col-sm-12" style="text-align:center;"><label>Actions</label><div><button type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float remove_btn" style="z-index: 1;" title="Remove"><i class="material-icons">remove</i></button></div></div></div>'; //New input field html 
            $(addButton).click(function(){ //Once add button is clicked
                if(x < maxField){ //Check maximum number of input fields
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); // Add field html
                } else {
                    alert("Hiện tại hệ thống hỗ trợ tối đa 5 cơ sở cho 1 nhà hàng. Liên hệ webmaster để có thể sở hữu nhiều hơn 5 cơ sở. Xin cảm ơn!");
                }
            });
            $(wrapper).on('click', '.remove_btn', function(e){ //Once remove button is clicked
                e.preventDefault();
                $(this).parent('div').parent('div').parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });
        </script>

@endsection