<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Restaurant;
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
		$this->middleware('auth')->except(['showFormCreate', 'create']);
		$this->middleware(function ($request, $next) {
			// $this->user = Auth::user();
			$this->restaurant = Restaurant::where('slug', '=', $request->route('restaurant_slug'))->first();
			if (Auth::check()) {
				$this->user = Auth::user();
			}
			else $this->user = null;
			if (!$this->restaurant) {
				if (Auth::check()) {
					return redirect()->route('restaurant.index')->with('error', 'Restaurant not found!');
				}
				return redirect()->route('homepage')->with('error', 'Restaurant not found!');
			}
			return $next($request);
		});
	}

	public function index () {
		return view('restaurant/order/index', ['restaurant' => $this->restaurant]);
	}

	public function showFormCreate () {
		return view('restaurant/order/create', ['restaurant' => $this->restaurant]);
	}

	public function create () {

	}
}
