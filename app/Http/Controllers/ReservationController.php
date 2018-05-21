<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
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
		return view('restaurant/reservation/index', ['restaurant' => $this->restaurant]);
	}

	public function showFormCreate(){
		return view('restaurant/reservation/create', ['restaurant' => $this->restaurant]);
	}

	public function create(Request $request, $restaurant_slug) {
		// create by staff
		$customer_name = $request->name;
		$customer_phone = $request->phone;
		$date = $request->date;
		$time = $request->time;
		$adult= $request->adult;
		$children= $request->children;
		$address_id= $request->address_id;
		$status= 'confirmed';
		$creator_id = $this->user->id;
		$last_editor_id = $this->user->id;
		$customer_requirement = $request->customer_requirement;
		$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'creator_id' => $creator_id, 'last_editor_id' => $last_editor_id, 'customer_requirement' => $customer_requirement];
		$book = $this->restaurant->reservations()->create($data);
		return redirect()->route('reservation.index', $restaurant_slug)->with('success', 'New reservation created!');
	}

	public function showFormEdit($restaurant_slug, $reservation_id) {
		$book = $this->restaurant->reservations->find($reservation_id);
		if (!$book) {
			return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
		}
		return view('restaurant/reservation/edit', ['restaurant' => $this->restaurant, 'reservation' => $book]);
	}

	public function update(Request $request, $restaurant_slug) {
		// create by staff
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
		$customer_requirement = $request->customer_requirement;
		$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'last_editor_id' => $last_editor_id, 'customer_requirement' => $customer_requirement];
		$book = $this->restaurant->reservations->find($request->id)->update($data);
		return redirect()->route('reservation.index', $restaurant_slug)->with('success', 'Reservation updated!');
	}

	public function delete (Request $request, $restaurant_slug, $reservation_id) {
		$book = $this->restaurant->reservations->find($reservation_id);
		if (!$book) {
			return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
		}
		$book->delete();
		return redirect()->route('reservation.index', $restaurant_slug)->with('success', 'Reservation deleted!');
	}
}
