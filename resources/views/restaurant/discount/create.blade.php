@extends('index')

@section('title')
	Create new discount for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb restaurant-breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('discount.index', $restaurant->slug)}}">Discounts</a></li>
                    <li class="active">Create new discount</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Create new discount
                        <small>Create new discount for {{$restaurant->name}}</small>
                    </h2>
                </div>
                    <div class="body">
                        <form method="POST" action="{{route('discount.create', ['restaurant_slug' => $restaurant->slug])}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="name">Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" required placeholder="Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if($restaurant->contacts->count() > 0)
                                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                        <label for="branch_id">Branch</label>
                                        <div class="demo-radio-button">
                                        @foreach($restaurant->contacts as $no => $contact)
                                        <input name="branch_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'checked' : ''}} value="{{$contact->id}}">
                                        <label for="address_{{$no + 1}}"> {{$contact->name}} - {{$contact->address}}</label>
                                        <br>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="type">Type of discount</label>
                                    <div class="demo-radio-button">
                                        <input name="type" type="radio" id="item" checked value="item">
                                        <label for="item">Apply for each individual item in a bill</label>
                                        <br>
                                        <input name="type" type="radio" id="total" value="total">
                                        <label for="total">Apply for the bill</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="discount_by">Discount by</label>
                                    <div class="demo-radio-button">
                                        <input name="discount_by" type="radio" id="discount_percent" checked value="discount_percent" onclick="percentOptionClick();">
                                        <label for="discount_percent">% Discount</label>
                                        <div class="form-line" id="discount_percent_input">
                                            <input type="number" min="1" max="99" style="width: 20%;" name="percent" class="form-control" id="input_percent" required>
                                        </div>
                                        <br>
                                        <input name="discount_by" type="radio" id="bonus_items" value="bonus_items" onclick="bonusOptionClick();">
                                        <label for="bonus_items">Bonus items</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 bonus-items" id="bonus-items" style="display: none;">
                                    <div class="row clearfix">
                                        <div class="col-md-5 col-lg-5 col-xs-12 col-sm-6">
                                            <label for="item_ids[0]">Bonus Item</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select name="item_ids[0]" class="form-control">
                                                        @foreach($restaurant->categories as $category)
                                                            <optgroup label="{{$category->name}}">
                                                                @foreach($category->items as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-lg-5 col-xs-12 col-sm-4">
                                            <label for="qty[0]">Quantity</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="1" name="qty[0]" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-xs-12 col-sm-2 pull-left">
                                            <label>Action</label>
                                            <div><button type="button" id="add_btn" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new item">
                                                <i class="material-icons">add</i>
                                            </button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <label for="price">Description</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea name="description" class="form-control" required placeholder="Details of description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t-15 waves-effect" style="margin-top: 0;">Create</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-script')
<script type="text/javascript">
    $(document).ready(function(){
        var x = 1; //Initial field counter is 1
        var addButton = $('#add_btn'); //Add button selector
        var wrapper = $('.bonus-items'); //Input field wrapper
        $(addButton).click(function(){ //Once add button is clicked
            var fieldHTML = `<div class="row clearfix"><div class="col-md-5 col-lg-5 col-xs-12 col-sm-6"><label for="item_ids[` + x + `]">Item</label><div class="form-group"><div class="form-line"><select name="item_ids[` + x + `]" class="form-control">`+
            `@foreach($restaurant->categories as $category)
                <optgroup label="{{$category->name}}">
                    @foreach($category->items as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </optgroup>
                @endforeach
            `
            + 
            `</select></div></div></div><div class="col-md-5 col-lg-5 col-xs-12 col-sm-4"><label for="qty[` + x + `]">Quantity</label><div class="form-group"><div class="form-line"><input type="number" name="qty[` + x +`]" class="form-control" min="1"></div></div></div><div class="col-md-2 col-lg-2 col-xs-12 col-sm-2 pull-left"><label>Action</label><div><button type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float remove_btn" style="z-index: 1;" title="Remove"><i class="material-icons">remove</i></button></div></div></div>`; //New input field html 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        });
        $(wrapper).on('click', '.remove_btn', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').parent('div').parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>
<script>
    function percentOptionClick () {
        document.getElementById('input_percent').type = 'number';
        document.getElementById('input_percent').required = 'true';
        document.getElementById('discount_percent_input').style.display = 'block';
        document.getElementById('bonus-items').style.display = 'none';
    }

    function bonusOptionClick () { 
        document.getElementById('input_percent').type = 'hidden';
        document.getElementById('input_percent').required = 'false';
        document.getElementById('discount_percent_input').style.display = 'none';
        document.getElementById('bonus-items').style.display = 'block';
    }
</script>
@endsection