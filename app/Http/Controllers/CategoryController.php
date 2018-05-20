<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudder;

class CategoryController extends Controller
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
            	return redirect()->route('restaurant.index')->with('error', 'You can not access this restaurant!');
            }
            return $next($request);
        });
    }

    public function index() {
    	return view('restaurant/menu/index', ['restaurant' => $this->restaurant]);
    }

    public function create(Request $request, $restaurant_slug){
		$name = $request->name;
		if (!$name) {
			return redirect()->route('category.index', $restaurant_slug)->with('error', 'Category name required!');
		}
    	if ($this->restaurant->categories->where('name', '=', $name)->first() === null) {
    		$category = $this->restaurant->categories()->create(['name' => $name, 'description' => $request->description]);
    		return redirect()->route('category.index', $restaurant_slug)->with('success', 'New category created!');
    	}
    	return redirect()->route('category.index', $restaurant_slug)->with('error', 'Duplicated category!');
    }

    public function update(Request $request, $restaurant_slug){
		$name = $request->name;
		if (!$name || !$request->id) {
			return redirect()->route('category.index', $restaurant_slug)->with('error', 'Category name required!');
		}
		$category = $this->restaurant->categories->find($request->id);
		if (!$category) {
			return redirect()->route('category.index', $restaurant_slug)->with('error', 'Category not found!');
		}
		if ($category->name == $name) {
			$category->description = $request->description;
			$category->save();
			return redirect()->route('category.index', $restaurant_slug)->with('success', 'Category updated!');
		}
		else {
			if ($this->restaurant->categories->where('name', '=', $name)->first()) {
				return redirect()->route('category.index', $restaurant_slug)->with('error', 'Duplicated category!');
			}
		}
    }

    public function delete($restaurant_slug, $category_id) {
    	$category = $this->restaurant->categories->find($category_id);
    	if (!$category) {
            if (strpos(url()->previous(), 'menu-list')) {
                return redirect()->route('category.list', $restaurant_slug)->with('error', 'Category not found!');
            }
    		return redirect()->route('category.index', $restaurant_slug)->with('error', 'Category not found!');
    	}
    	else {
    		$category->delete();
            if (strpos(url()->previous(), 'menu-list')) {
                return redirect()->route('category.list', $restaurant_slug)->with('success', 'Category deleted!');
            }
    		return redirect()->route('category.index', $restaurant_slug)->with('success', 'Category deleted!');
    	}
    }

    public function list() {
        return view('restaurant/menu/list', ['restaurant' => $this->restaurant]);
    }

    public function show($restaurant_slug, $category_slug) {
        $category = $this->restaurant->categories->where('slug', '=', $category_slug)->first();
        if (!$category) {
            return redirect()->route('category.index', $restaurant_slug)->with('error', 'Category not found!');
        }
        return view('restaurant/menu/show', ['category' => $category, 'restaurant' => $this->restaurant]);
    }
}
