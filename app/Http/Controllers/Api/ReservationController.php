<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Restaurant;
class ReservationController extends Controller
{
	private $restaurant;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$this->restaurant = Restaurant::where('slug', '=', $request->route('restaurant_slug'))->first();
			if (!$this->restaurant) {
				return 404;
			}
			return $next($request);
		});
	}

	public function index() {
		return $this->restaurant->reservations;
	}

	public function create(Request $request, $restaurant_slug) {
		// create by staff
		$customer_name = $request->customer_name;
		$customer_phone = $request->customer_phone;
		$date = $request->date;
		$time = $request->time;
		$adult= $request->adult;
		$children= $request->children;
		$address_id= $request->address_id;
		$status= 'pending';
		$customer_requirement = $request->customer_requirement;
		$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'created_by_bot' => 1, 'customer_requirement' => $customer_requirement];
		$book = $this->restaurant->reservations()->create($data);
		return $book;
	}

	// public function update(Request $request, $restaurant_slug) {
	// 	// create by staff
	// 	$book = $this->restaurant->reservations->find($request->id);
	// 	if (!$book) {
	// 		return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
	// 	}
	// 	$customer_name = $request->name;
	// 	$customer_phone = $request->phone;
	// 	$date = $request->date;
	// 	$time = $request->time;
	// 	$adult= $request->adult;
	// 	$children= $request->children;
	// 	$address_id= $request->address_id;
	// 	$status= $request->status;
	// 	$last_editor_id = $this->user->id;
	// 	$customer_requirement = $request->customer_requirement;
	// 	$data = ['customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'last_editor_id' => $last_editor_id, 'customer_requirement' => $customer_requirement];
	// 	$book = $this->restaurant->reservations()->update($data);
	// 	return redirect()->route('reservation.index', $restaurant_slug)->with('success', 'Reservation updated!');
	// }

	// public function delete (Request $request, $restaurant_slug, $reservation_id) {
	// 	$book = $this->restaurant->reservations->find($reservation_id);
	// 	if (!$book) {
	// 		return redirect()->route('reservation.index', $restaurant_slug)->with('error', 'Reservation order not found!');
	// 	}
	// 	$book->delete();
	// 	return redirect()->route('reservation.index', $restaurant_slug)->with('success', 'Reservation deleted!');
	// }
}
