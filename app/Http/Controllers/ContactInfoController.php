<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ContactInfoController extends Controller
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
            $this->restaurant = $this->user->restaurants->where('slug', '=', $request->route('slug'))->first();
            if (!$this->restaurant) {
            	return redirect()->route('restaurant.index')->with('error', 'You can not access this restaurant!');
            }
            return $next($request);
        });
    }

    public function index() {
    	return view('restaurant/contact/index', ['restaurant' => $this->restaurant]);
    }

    public function update(Request $request) {

    }

    public function create(Request $request) {
    	
    }
}
