<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use App\Reservation;
use App\Customer;
use App\Order;
use App\Item;
use Input;
use Validator;
use Excel;
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
	public function showFormCreateOrder ($restaurant_slug, $customer_psid) {
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $customer_psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
		return view('customer/new-order', ['restaurant' => $this->restaurant, 'customer' => $customer]);
	}
	public function showOrderCart (Request $request, $restaurant_slug, $customer_psid) {
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $customer_psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
		// $items = $request->items;
		$quantity = $request->quantity;
		if (!$quantity) {
			return response()->view('info/cart-fail', ['restaurant' => $this->restaurant, 'customer' => $customer]);
		}
		foreach ($quantity as $key => $value) {
			if (!is_numeric($value) || !$value) {
				unset($quantity[$key]);
			}
		}
		if (count($quantity) == 0) {
			return response()->view('info/cart-fail', ['restaurant' => $this->restaurant, 'customer' => $customer]);
		}
		$items = $this->restaurant->items->whereIn('id', array_keys($quantity));

		return response()->view('customer/checkout', ['items' => $items, 'quantity' => $quantity, 'restaurant' => $this->restaurant, 'customer' => $customer]);
	}

	public function createOrder(Request $request, $restaurant_slug, $customer_psid){
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $customer_psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
		$validator = Validator::make($request->all(), [
			'name'	=> 'required',
			'phone'	=> 'required',
			'address'	=> 'required',
			'email' => 'nullable|email'
		], [
			'required' => 'The :attribute field is missing',
			'email' => 'The :attribute must be a valid email address'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}
		$quantity = $request->quantity;
		if (!$quantity) {
			return response()->view('info/cart-fail', ['restaurant' => $this->restaurant, 'customer' => $customer]);
		}
		foreach ($quantity as $key => $value) {
			if (!is_numeric($value) || !$value) {
				unset($quantity[$key]);
			}
		}
		if (count($quantity) == 0) {
			return response()->view('info/cart-fail', ['restaurant' => $this->restaurant, 'customer' => $customer]);
		}
		// update user info
		$customer->name = $request->name;
		$customer->phone = $request->phone;
		$customer->email = $request->email;
		$customer->address = $request->address;
		$customer->save();

		// now create order
		$total = 0;
		foreach ($quantity as $key => $value) {
			$total += $this->restaurant->items->find($key)->price * (int) $value;
		}

		$order = $this->restaurant->orders()->create([
			"customer_id" => $customer->id,
			"customer_phone" => $request->phone,
			"customer_address" => $request->address,
			"customer_note" => $request->requirement,
			"created_by_bot" => 1,
			"total" => $total,
			"branch_id" => $request->address_id,
			"total" => $total,
			"customer_name" => $request->name
		]);
		$order->money = $order->money();
		$order->save();

		foreach ($quantity as $key => $value) {
			$order->items()->attach($key, ['qty' => $value, 'price' => Item::find($key)->price]);
		}
		event(new \App\Events\OrderUpdated($order));
		return response()->view('info/food-order-success');
	}

	public function showFormCreateReservation($restaurant_slug, $psid) {
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
		return view('customer/reservation-create', ['restaurant' => $this->restaurant, 'customer' => $customer]);
	}

	public function review($restaurant_slug, $reservation_id) {
		$reservation = $this->restaurant->reservations->find($reservation_id);
		if (!$reservation) {
			return response()->view('errors/404');
		}
		return view('customer/reservation-review', ['restaurant' => $this->restaurant, 'reservation' => $reservation]);
	}

	public function cancel_reservation($restaurant_slug, $reservation_id) {
		$reservation = $this->restaurant->reservations->find($reservation_id);
		if (!$reservation) {
			return response()->view('errors/404');
		}
		$customer = $reservation->customer;
		$reservation->status = 'canceled';
		$reservation->save();
		event(new \App\Events\ReservationUpdated($reservation));
		// Send message to customer via chatbot
		$this->restaurant->bot->displaySenderAction($customer->app_scoped_id);
		$this->restaurant->bot->replyReservation($reservation, $customer);
		return response()->view('info/reservation-canceled');
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

	public function create_reservation(Request $request) {
		$psid = $request->customer_psid;
		$customer = $this->restaurant->customers->where('app_scoped_id', '=', $psid)->first();
		if (!$customer) {
			return response()->view('errors/404');
		}
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
			'email'	=> 'The :attribute must be a valid email'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}
		// created_by_bot
		$customer_id = $psid;
		$created_by_bot = 1;
		$customer_name = $request->name;
		$customer_phone = $request->phone;
		$date = $request->date;
		$time = $request->time;
		$adult = $request->adult;
		$children = $request->children;
		$address_id = $request->address_id;
		$status = 'pending';
		$creator_id = null;
		$last_editor_id = null;
		$email = $request->email;
		$customer_requirement = $request->requirement;
		$data = ['customer_id' => $customer->id, 'created_by_bot' => $created_by_bot, 'customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'date' => $date, 'time' => $time, 'adult' => $adult, 'children' => $children, 'address_id' => $address_id, 'status' => $status, 'customer_requirement' => $customer_requirement, 'email' => $email];
		$book = $this->restaurant->reservations()->create($data);
		event(new \App\Events\ReservationUpdated($book));

		// Update customer information
		$customer->updateInformation();
		$customer->name = $customer_name;
		$customer->phone = $customer_phone;
		$customer->email = $email;
		$customer->save();

		// Send message to customer via chatbot
		$this->restaurant->bot->displaySenderAction($customer->app_scoped_id);
		$this->restaurant->bot->replyReservation($book, $customer);
		return response()->view('info/order-success');
	}

	public function showMenu() {
		return view('customer/menu', ['restaurant' => $this->restaurant]);
	}

	public function export() {
		return Excel::download(new \App\Exports\CustomersExport($this->restaurant->id), 'customers.xlsx');
	}
}
