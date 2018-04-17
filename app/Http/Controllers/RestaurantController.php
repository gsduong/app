<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
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
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('restaurant/index');
    }

    public function showCreateForm() {
        return view('restaurant/create');
    }

    public function create(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required',
            'fb_page_id' => 'unique:restaurants'
        ]);
        $user_id = $this->user->id;
        $restaurant = $this->user->restaurants()->create([
            'name' => $request->name,
            'fb_page_id' => $request->fb_page_id,
            'fb_page_access_token' => $request->fb_page_access_token,
            'creator_id' => $user_id
        ]);
        return redirect('/r');
    }
}
