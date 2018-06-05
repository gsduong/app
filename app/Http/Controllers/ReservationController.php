<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Input;
use DateTime;
use DateTimeZone;
class ReservationController extends Controller
{
	private $user;
	private $restaurant;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->user = Auth::user();
			$this->restaurant = $this->user->restaurants->where('slug', '=', $request->route('restaurant_slug'))->first();
			if (!$this->restaurant) {
				return redirect()->route('restaurant.index')->with('error', 'Restaurant not found!');
			}
			return $next($request);
		});
	}

	public function index() {
		$reservations = $this->restaurant->reservations()->newQuery();
		if (Input::get('status')) {
			$reservations->where('status', Input::get('status'));
		}
		if (Input::get('name')) {
			$reservations->where('customer_name', 'like', '%' . Input::get('name') . '%');
		}
		if (Input::get('phone')) {
			$reservations->where('customer_phone', Input::get('phone'));
		}

		if (Input::get('date')) {
			$reservations->whereDate('date', Input::get('date'));
		}
		$reservations = $reservations->orderBy('date', 'asc')->orderBy('time', 'asc')->paginate(5);
		return view('restaurant/reservation/index', ['restaurant' => $this->restaurant, 'reservations' => $reservations]);
	}

	public function showFormCreate(){
		return view('restaurant/reservation/staff-create-book', ['restaurant' => $this->restaurant]);
	}

	public function create(Request $request, $restaurant_slug) {
		$validator = Validator::make($request->all(), [
			'name'	=> 'required',
			'phone'	=> 'required',
			'date'	=> 'required|after_or_equal:' . date('Y-m-d'),
			'time'	=> 'required',
			'adult'	=> 'required|min:1',
			'children' => 'required|min:0',
			'email' => 'nullable|email'
		], [
			'required' => 'The :attribute field is missing',
			'date.after_or_equal' => 'The :attribute must be from :date',
			'adult.min'	=> 'The :attribute must be at least :min',
			'children'	=> 'The :attribute must be at least :min',
			'email' => 'The :attribute must be a valid email address'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}
		// create by staff
		$customer_name = $request->name;
		$customer_phone = $request->phone;
		$date = $request->date;
		$time = $request->time;
		$adult= $request->adult;
		$children= $request->children;
		$address_id= $request->address_id;
		$status= 'pending';
		$creator_id = $this->user->id;
		$last_editor_id = $this->user->id;
		$customer_requirement = $request->requirement;
		$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'creator_id' => $creator_id, 'last_editor_id' => $last_editor_id, 'customer_requirement' => $customer_requirement, 'email' => $request->email];
		$book = $this->restaurant->reservations()->create($data);
		event(new \App\Events\ReservationUpdated($book));
		return redirect()->route('reservation.index', $restaurant_slug);
	}

	public function showFormEdit($restaurant_slug, $reservation_id) {
		$book = $this->restaurant->reservations->find($reservation_id);
		if (!$book) {
			return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
		}
		return view('restaurant/reservation/staff-edit-book', ['restaurant' => $this->restaurant, 'reservation' => $book]);
	}

	public function update(Request $request, $restaurant_slug) {
		$validator = Validator::make($request->all(), [
			'name'	=> 'required',
			'phone'	=> 'required',
			'date'	=> 'required|after_or_equal:' . date('Y-m-d'),
			'time'	=> 'required',
			'adult'	=> 'required|min:1',
			'children' => 'required|min:0',
			'email'	=> 'nullable|email'
		], [
			'required' => 'The :attribute field is missing',
			'date.after_or_equal' => 'The :attribute must be from :date',
			'adult.min'	=> 'The :attribute must be at least :min',
			'children'	=> 'The :attribute must be at least :min',
			'email'	=> 'The :attribute must be a valid email address'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}
		$book = $this->restaurant->reservations->find($request->id);
		if (!$book) {
			return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
		}
		$customer_name = $request->name;
		$customer_phone = $request->phone;
		$date = $request->date;
		$time = $request->time;
		$adult= $request->adult;
		$children= $request->children;
		$address_id= $request->address_id;
		$status= $request->status;
		$last_editor_id = $this->user->id;
		$customer_requirement = $request->requirement;
		$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'last_editor_id' => $last_editor_id, 'customer_requirement' => $customer_requirement, 'email' => $request->email];
		$this->restaurant->reservations->find($request->id)->update($data);
		$book = $this->restaurant->reservations->find($request->id);
		event(new \App\Events\ReservationUpdated($book));
		if ($book->created_by_bot) {
			// Send message to customer via chatbot
			$customer = $book->customer;
			$this->restaurant->bot->displaySenderAction($customer->app_scoped_id);
			$this->restaurant->bot->replyReservation($book, $customer);
		}
		return redirect()->route('reservation.index', $restaurant_slug);
	}

	public function delete (Request $request, $restaurant_slug, $reservation_id) {
		$book = $this->restaurant->reservations->find($reservation_id);
		if (!$book) {
			return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
		}
		$this->restaurant->reservations->find($reservation_id)->delete();
		// event(new \App\Events\ReservationUpdated($book));
		return redirect()->route('reservation.index', $restaurant_slug);
	}

}
