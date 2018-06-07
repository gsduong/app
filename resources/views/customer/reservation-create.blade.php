@extends('customer.index')

@section('title')
    RESERVATION
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0;">
        <div class="card">
            <div class="body">
            <form method="POST" autocomplete="off" action="{{route('customer.reservation.create', ['restaurant_slug' => $restaurant->slug])}}" id="reservation-form">
                @csrf
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <b>Name</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                            	<input type="hidden" name="customer_psid" value="{{$customer->app_scoped_id}}">
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
                    <div class="col-xs-6">
                        <b>Date</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input class="form-control" name="date" readonly onchange="updateLabel(this)" placeholder="Ex: 2018-06-01" id="date" value="{{ old('date') }}" required="true" min={{date('Y-m-d')}}>
                            </div>
                        </div>
                        <label id="date-error" class="validation-error-label" for="date"><small>{{ $errors->first('date') }}</small></label>
                    </div>
                    <div class="col-xs-6">
                        <b>Time</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">access_time</i>
                            </span>
                            <div class="form-line">
                                <input value="{{ old('time') }}" class="form-control" name="time" readonly onchange="updateLabel(this)" placeholder="Ex: 23:59" id="time" required="true">
                            </div>
                        </div>
                        <label id="time-error" class="validation-error-label" for="time"><small>{{ $errors->first('time') }}</small></label>
                    </div>
                    <div class="col-xs-6">
                        <b>Adults</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">people</i>
                            </span>
                            <div class="form-line">
                                <input type="number" id="adult" onfocusout="updateLabel(this)" name="adult" class="form-control" placeholder="Adults" required="true" min="1" value="{{ old('adult') }}">
                            </div>
                        </div>
                        <label id="adult-error" class="validation-error-label" for="adult"><small>{{ $errors->first('adult') }}</small></label>
                    </div>
                    <div class="col-xs-6">
                        <b>Children</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">child_care</i>
                            </span>
                            <div class="form-line">
                                <input type="number" id="children" name="children" onfocusout="updateLabel(this)" required class="form-control" placeholder="Children" value="{{ old('children')}}" min="0">
                            </div>
                        </div>
                        <label id="children-error" class="validation-error-label" for="children"><small>{{ $errors->first('children') }}</small></label>
                    </div>
                    @if($restaurant->contacts->count() > 0)
                        <div class="col-xs-12">
                            <b>Branch</b>
                            <div class="input-group" style="margin-bottom: 0;">
                                <div class="demo-radio-button">
                                    <div class="row">
                                    @foreach($restaurant->contacts as $no => $contact)
                                    <div class="col-xs-12" style="padding-left: 11px; margin-bottom: 5px;">
                                        <input name="address_id" type="radio" id="address_{{$no + 1}}" {{$no == 0 ? 'checked' : ''}} value="{{$contact->id}}">
                                        <label for="address_{{$no + 1}}">{{$contact->address}}</label>
                                    </div>

                                    @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-12" style="margin-bottom: 20px;">
                        <b>Requirements</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">event_note</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="requirement" class="form-control" placeholder="Your additional requirement" value="{{ old('requirement') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-block btn-lg btn-success waves-effect" onclick="formValidate()">BOOK</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
@section('extra-script')
    <script>
        date.onclick = function() {
            var d = new Date();
            var datePicker = new DateTimePicker.Date({
                lang: 'en-EN',
                default: date.value,
                min: '{{date('Y-m-d')}}'
            })
            datePicker.on('selected', function (formatDate, now) {
                console.log('selected date: ', formatDate, now);
                date.value = formatDate;
                date.onchange();
            })
            datePicker.on('cleared', function () {
                console.log('cleared date');
                date.value = '';
                date.onchange();
            })
        }
        time.onclick = function() {
            var timePicker = new DateTimePicker.Time({
                default: time.value,
                min: '00:00',
                max: '23:59',
                minuteStep: 1
            })
            timePicker.on('selected', function (formatTime, now) {
                console.log('selected time: ', formatTime, now);
                time.value = formatTime;
                time.onchange();
            })
            timePicker.on('cleared', function () {
                console.log('cleared time');
                time.value = '';
                time.onchange();
            })
        }
    </script>
    <script>
        function formValidate(){
            $flag = true;
            var form = document.getElementById("reservation-form");
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
            var adult = document.getElementById("adult");
            if (!adult.checkValidity()) {
                document.getElementById("adult-error").style.display = "block";
                document.getElementById("adult-error").innerHTML = adult.validationMessage;
                $flag = false;
            } else {
                document.getElementById("adult-error").style.display = "none";
            }
            var children = document.getElementById("children");
            if (!children.checkValidity()) {
                document.getElementById("children-error").style.display = "block";
                document.getElementById("children-error").innerHTML = children.validationMessage;
                $flag = false;
            } else {
                document.getElementById("children-error").style.display = "none";
            }
            var date = document.getElementById("date");
            if (!date.checkValidity() || !Date.parse(date.value) || Date.parse(date.value) < Date.parse(formatDate(new Date()))) {
            	console.log("Picked: " + date.value + " - " + "Current: " + formatDate(new Date()))
                document.getElementById("date-error").style.display = "block";
                document.getElementById("date-error").innerHTML = date.validationMessage || "Please pick date again.";
                $flag = false;
            } else {
                document.getElementById("date-error").style.display = "none";
            }
            var time = document.getElementById("time");
            if (!time.checkValidity() || !Date.parse('1970-01-01T' + time.value)) {
                document.getElementById("time-error").style.display = "block";
                document.getElementById("time-error").innerHTML = time.validationMessage || "Please pick time again.";
                $flag = false;
            } else {
                document.getElementById("time-error").style.display = "none";
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
		function formatDate(date) {
		    var d = new Date(date),
		        month = '' + (d.getMonth() + 1),
		        day = '' + d.getDate(),
		        year = d.getFullYear();

		    if (month.length < 2) month = '0' + month;
		    if (day.length < 2) day = '0' + day;

		    return [year, month, day].join('-');
		}
    </script>
@endsection