<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook;
use App\Restaurant;
use App\User;
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
        $restaurants = $this->user->restaurants;
        return view('restaurant/index', ['restaurants' => $restaurants]);
    }

    public function selectPage() {
        // get the list of pages
        // dd($this->get_available_pages());
        $pages = json_decode($this->user->pages, true);
        return view('restaurant/select-page', ['pages' => $pages]);
    }

    public function create(Request $request) {
        $page_id = $request->fb_page_id;
        if ($this->user->hasPage($page_id)) {
            $user_id = $this->user->id;
            $restaurant = $this->user->restaurants()->create([
                'name' => $request->name,
                'fb_page_id' => $page_id,
                'fb_page_access_token' => $this->user->get_page_access_token_from_page_id($page_id),
                'creator_id' => $user_id,
                'avatar' => User::get_page_picture_url_from_page_id($page_id)
            ]);

            // update user's pages
            $this->user->update_pages();
            return redirect('/r')->with('success', 'Successfully created new restaurant!');
        }
        return redirect('/r')->with('error', 'Cannot create new restaurant with the given page id!');
    }

    public function showFormCreate() {
        return view('restaurant/create');
    }

    public function showFormCreateWithId(Request $request) {
        $page_id = $request->page_id;
        if ($page = $this->user->get_page_from_page_id($page_id)) {
            return view('restaurant/create', ['page' => $page]);
        }
        return redirect()->route('restaurant.select-page')->with('error' , 'Page not found');
    }

    public function delete($restaurant_id) {
        $restaurant = $this->user->restaurants->find($restaurant_id);
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->with('error', 'Item not found!');
        }
        else {
            $restaurant->delete();
            $this->user->update_pages();
            return redirect()->route('restaurant.index')->with('success', 'Item deleted!');
        }
    }
}
