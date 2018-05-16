                            <div class="form-group address-form">
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
                            </div>
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