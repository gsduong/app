<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use Input;
class CustomerController extends Controller
{
	private $customer;
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
				return response()->view('errors/404');
			}
			return $next($request);
		});
	}
	public function showFormCreateOrder () {
		return view('customer.new-order', ['restaurant' => $this->restaurant]);
	}

	public function showFormCreateReservation($restaurant_slug, $psid) {
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
		return view('customer.reservation', ['restaurant' => $this->restaurant, 'customer' => $customer]);
	}

	public function index(){
		$customers = $this->restaurant->customers()->newQuery();
		if (Input::get('first_name')) {
			$customers->where('first_name', 'like', '%' . Input::get('first_name') . '%');
		}
		if (Input::get('last_name')) {
			$customers->where('last_name', 'like', '%' . Input::get('last_name') . '%');
		}
		$customers = $customers->orderBy('created_at', 'desc')->paginate(5);
		return view('restaurant/customer/index', ['restaurant' => $this->restaurant, 'customers' => $customers]);
	}
}
