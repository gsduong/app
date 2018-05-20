<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Bitly;

class ItemController extends Controller
{
	private $user;
	private $restaurant;
	private $category;

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
			$this->category = $this->restaurant->categories->where('slug', '=', $request->route('category_slug'))->first();
			if (!$this->category) {
				return redirect()->route('category.index', $request->route('category_slug'))->with('error', 'Category not found');
			}
			return $next($request);
		});
	}

	public function showFormCreate($restaurant_slug, $category_slug) {
		return view('restaurant/menu/item/create', ['category' => $this->category, 'restaurant' => $this->restaurant]);
	}

	public function create(Request $request, $restaurant_slug, $category_slug) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'item_url' => 'nullable|url'
        ]);
        if ($validator->fails()) {
            return redirect()->route('item.show-form-create', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug])->withError('Name and price are required! Item URL must be a valid URL!')->withInput();
        }
        $item = $this->category->items->where('name', '=', $request->name)->first();
        if ($item) {
        	return redirect()->route('item.show-form-create', ['category_slug' => $category_slug, 'restaurant_slug' => $restaurant_slug])->with('error', 'Duplicated item!')->withInput();
        }
        $item_url = strlen($request->item_url) > 25 ? Bitly::getUrl($request->item_url) : $request->item_url;
        $item = $this->category->items()->create(['name' => $request->name, 'price' => $request->price, 'unit' => $request->unit, 'item_url' => $item_url]);
		return redirect()->route('category.show', ['category_slug' => $category_slug, 'restaurant_slug' => $restaurant_slug])->with('success', 'New item created');
	}

	public function delete($restaurant_slug, $category_slug, $item_id) {
		$item = $this->category->items->find($item_id);
		if (!$item) {
			return redirect()->route('category.show', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug])->withError('Item not found');
		}
		$item->delete();
		return redirect()->route('category.show', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug])->withSuccess('Item deleted!');
	}

	public function showFormEdit($restaurant_slug, $category_slug, $item_id) {
		$item = $this->category->items->find($item_id);
		if (!$item) {
			return redirect()->route('category.show', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug])->withError('Item not found');
		}

		return view('restaurant/menu/item/edit', ['category' => $this->category, 'restaurant' => $this->restaurant, 'item' => $item]);
	}

	public function update(Request $request, $restaurant_slug, $category_slug) {
		$item = $this->category->items->find($request->item_id);
		if (!$item) {
			return redirect()->route('category.show', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug])->withError('Item not found');
		}
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'item_url' => 'nullable|url'
        ]);
        if ($this->category->items->where('id', '<>', $request->item_id)->where('name', '=', $request->name)->first()) {
        	return redirect()->route('item.show-form-edit', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug, 'item_id' => $request->item_id])->withError('Name already exists!');
        }
        if ($validator->fails()) {
            return redirect()->route('item.show-form-edit', ['restaurant_slug' => $restaurant_slug, 'category_slug' => $category_slug, 'item_id' => $request->item_id])->withError('Name and price are required! Item URL must be a valid URL!');
        }
        $item->name = $request->name;
        $item->price = $request->price;
        $item->unit = $request->unit;
        $item_url = strlen($request->item_url) > 25 ? Bitly::getUrl($request->item_url) : $request->item_url;
        $item->item_url = $item_url;
        $item->save();
		return redirect()->route('item.show-form-edit', ['category_slug' => $category_slug, 'restaurant_slug' => $restaurant_slug, 'item_id' => $request->item_id])->with('success', 'Item updated!');
	}
}
