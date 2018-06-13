<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Restaurant;
use Input;
class OrderController extends Controller
{
    //
    private $restaurant;
    private $user;
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
				return response()->view('errors/404');
			}
			return $next($request);
		});
	}

	public function index () {
		$orders = $this->restaurant->orders()->newQuery();
		if (Input::get('status')) {
			$orders->where('status', Input::get('status'));
		}
		if (Input::get('name')) {
			$orders->where('customer_name', 'like', '%' . Input::get('name') . '%');
		}
		if (Input::get('phone')) {
			$orders->where('customer_phone', 'like', '%' . Input::get('phone') . '%');
		}

		if (Input::get('date')) {
			$orders->whereDate('created_at', Input::get('date'));
		}
		$orders = $orders->orderBy('created_at', 'desc')->paginate(5);
		return view('restaurant/order/index', ['restaurant' => $this->restaurant, 'orders' => $orders]);
	}

	public function showFormCreate () {
		return view('restaurant/order/create', ['restaurant' => $this->restaurant]);
	}

	public function showFormEdit ($restaurant_slug, $order_id) {
		$order = $this->restaurant->orders->find($order_id);
		if (!$order) {
			return response()->view('errors/404');
		}
		return view('restaurant/order/edit', ['restaurant' => $this->restaurant, 'order' => $order]);
	}

	public function confirmOrder (Request $request, $restaurant_slug, $order_id) {
		$order = $this->restaurant->orders->find($order_id);
		if (!$order || $order_id != $request->order_id) {
			return response()->view('errors/404');
		}
		if ($order->status != "pending") {
			return redirect()->route('order.index', $restaurant_slug)->withError('The order must be pending');
		}
		$order->status = "confirmed";
		$order->money = $order->money();
		$order->last_editor_id = $this->user->id;
		$order->save();
		event(new \App\Events\OrderUpdated($order));
		return redirect()->back()->withSuccess('New food order has been confirm. Please deliver your food');
	}

	public function deliverOrder (Request $request, $restaurant_slug, $order_id) {
		$order = $this->restaurant->orders->find($order_id);
		if (!$order || $order_id != $request->order_id) {
			return response()->view('errors/404');
		}
		if ($order->status != "confirmed") {
			return redirect()->route('order.index', $restaurant_slug)->withError('The order must be confirmed first');
		}
		$order->status = "delivering";
		$order->money = $order->money();
		$order->last_editor_id = $this->user->id;
		$order->save();
		event(new \App\Events\OrderUpdated($order));

		return redirect()->route('order.index', $restaurant_slug)->withSuccess('New food order is being delivered');
	}
	public function cancelOrder (Request $request, $restaurant_slug, $order_id) {
		$order = $this->restaurant->orders->find($order_id);
		if (!$order || $order_id != $request->order_id) {
			return response()->view('errors/404');
		}
		if ($order->status != "pending") {
			return redirect()->route('order.index', $restaurant_slug)->withError('Only pending orders are able to be canceled');
		}
		$order->status = "canceled";
		$order->money = $order->money();
		$order->last_editor_id = $this->user->id;
		$order->save();
		event(new \App\Events\OrderUpdated($order));
		return redirect()->route('order.index', $restaurant_slug)->withSuccess('New food order is canceled');
	}
}
