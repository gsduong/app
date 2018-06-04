@extends('customer.index')

@section('title')
    RESERVATION
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
<div class="row clearfix">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
            <form method="POST" action="{{-- {{route('reservation.create', ['restaurant_slug' => $restaurant->slug])}} --}}">
                @csrf
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <b>Name</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Please provide your name" required="true" value="{{ old('name') }}">
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
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Please provide your phone number" required="true" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <label id="phone-error" class="validation-error-label" for="phone"><small>{{ $errors->first('phone') }}</small></label>
                    </div>
                    <div class="col-xs-6">
                        <b>Date</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="date" placeholder="Ex: 2018-06-01" id="date" value="{{ old('date') }}" required="true" min={{date('Y-m-d')}}>
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
                                <input type="text" value="{{ old('time') }}" class="form-control" name="time" placeholder="Ex: 23:59" id="time" required="true">
                            </div>
                        </div>
                        <label id="time-error" class="validation-error-label" for="time"><small>{{ $errors->first('time') }}</small></label>
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
                    <div class="col-xs-6">
                        <b>Adults</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">people</i>
                            </span>
                            <div class="form-line">
                                <input type="number" id="adult" name="adult" class="form-control" placeholder="Adults" required="true" min="1" value="{{ old('adult') }}">
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
                                <input type="number" id="children" name="children" class="form-control" placeholder="Children" value="{{ old('children') }}" min="0">
                            </div>
                        </div>
                        <label id="children-error" class="validation-error-label" for="children"><small>{{ $errors->first('children') }}</small></label>
                    </div>
                    <div class="col-xs-12">
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
                        <button type="submit" class="btn btn-block btn-lg btn-success waves-effect" onclick="formValidate()">BOOK</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
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
                console.log('selected date: ', formatDate, now)
                date.value = formatDate
            })
            datePicker.on('cleared', function () {
                console.log('cleared date')
                date.value = ''
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
                console.log('selected time: ', formatTime, now)
                time.value = formatTime
            })
            timePicker.on('cleared', function () {
                console.log('cleared time')
                time.value = ''
            })
        }
    </script>
    <script>
        function formValidate(){
            $flag = true;
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
            if (!date.checkValidity()) {
                document.getElementById("date-error").style.display = "block";
                document.getElementById("date-error").innerHTML = date.validationMessage;
                $flag = false;
            } else {
                document.getElementById("date-error").style.display = "none";
            }
            var time = document.getElementById("time");
            if (!time.checkValidity()) {
                document.getElementById("time-error").style.display = "block";
                document.getElementById("time-error").innerHTML = time.validationMessage;
                $flag = false;
            } else {
                document.getElementById("time-error").style.display = "none";
            }
            return $flag;
        }
    </script>
@endsection