<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContactInfo;
use App\Restaurant;
class AddressController extends Controller
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

	public function index (){
		return $this->restaurant->contacts;
	}
}
