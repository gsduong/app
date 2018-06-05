@extends('customer.index')

@section('title')
    RESERVATION REVIEW
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0;">
        <div class="card">
            <div class="body">
            <form>
                @csrf
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <b>Name</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="text" name="name" id="name" class="form-control" value="{{$reservation->customer_name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <b>Phone</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">phone</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="text" name="phone" id="phone" class="form-control" value="{{$reservation->customer_phone}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <b>Email</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="email" value="{{ $reservation->customer->email }}" class="form-control" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <b>Date</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input disabled class="form-control" name="date" readonly id="date" value="{{$reservation->date}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <b>Time</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">access_time</i>
                            </span>
                            <div class="form-line">
                                <input disabled value="{{$reservation->time}}" class="form-control" name="time" readonly id="time" required="true">
                            </div>
                        </div>
                    </div>
                    @if($restaurant->contacts->count() > 0)
                        <div class="col-xs-6">
                            <b>Branch</b>
                            <div class="input-group" style="margin-bottom: 0;">
                                <div class="demo-radio-button">
                                    <div class="row">
                                    @foreach($restaurant->contacts as $no => $contact)
                                    <div class="col-xs-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$reservation->address_id == $contact->id ? 'block' : 'none'}};">
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
                        <b>Status</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="demo-radio-button">
                                <div class="row">
                                    <div class="col-xs-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$reservation->status == 'pending' ? 'block' : 'none'}};">
                                        <input name="status" type="radio" id="pending" {{$reservation->status == 'pending' ? 'checked' : ''}}>
                                        <label for="pending">Pending</label>
                                    </div>
                                    <div class="col-xs-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$reservation->status == 'confirmed' ? 'block' : 'none'}};">
                                        <input name="status" type="radio" id="confirmed" {{$reservation->status == 'confirmed' ? 'checked' : ''}}>
                                        <label for="confirmed">Confirmed</label>
                                    </div>
                                    <div class="col-xs-12" style="padding-left: 11px; margin-bottom: 5px; display: {{$reservation->status == 'canceled' ? 'block' : 'none'}};">
                                        <input name="status" type="radio" id="canceled" {{$reservation->status == 'canceled' ? 'checked' : ''}}>
                                        <label for="canceled">Canceled</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <b>Adults</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">people</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="number" id="adult" name="adult" class="form-control" value="{{$reservation->adult}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <b>Children</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">child_care</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="number" id="children" name="children" class="form-control" value="{{$reservation->children}}" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 20px;">
                        <b>Requirements</b>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-addon">
                                <i class="material-icons">event_note</i>
                            </span>
                            <div class="form-line">
                                <input disabled type="text" name="requirement" class="form-control" value="{{$reservation->customer_requirement}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        @if($reservation->status == 'canceled')
                        <button class="btn btn-block btn-lg btn-danger waves-effect" disabled>CANCEL</button>
                        @else
                        <a href="{{route('customer.reservation.cancel', ['restaurant_slug' => $restaurant->slug, 'reservation_id' => $reservation->id])}}" class="btn btn-block btn-lg btn-danger waves-effect" {{$reservation->status == 'canceled' ? 'disabled' : ''}}>CANCEL</a>
                        @endif
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
@endsection