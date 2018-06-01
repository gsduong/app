<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Input;
use Validator;

class DiscountController extends Controller
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
				return redirect()->route('restaurant.index')->with('error', 'Restaurant not found!');
			}
			return $next($request);
		});
	}

	public function index() {
		$discounts = $this->restaurant->discounts()->newQuery();
		if (Input::get('branch_id')) {
			$discounts->where('branch_id', Input::get('branch_id'));
		}
		$discounts = $discounts->orderBy('name', 'asc')->paginate(5);
		return view('restaurant/discount/index', ['restaurant' => $this->restaurant, 'discounts' => $discounts]);
	}

	public function showFormCreate(){
		return view('restaurant/discount/create', ['restaurant' => $this->restaurant]);
	}

	public function create(Request $request, $restaurant_slug) {
		$validator = Validator::make($request->all(), [
			'name'	=> 'required',
			'description'	=> 'required',
			'type'	=> 'required',
			'branch_id'	=> 'nullable',
			'discount_by'	=> 'required',
			'percent' => 'nullable|min:1,max:99'
		], [
			'required' => 'The :attribute field is missing',
			'min'	=> 'The :attribute must be at least :min',
			'max'	=> 'The :attribute must be at most :max'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withError('Something is wrong with provided information');
		}
		$name = $request->name;
		$branch_id = $request->branch_id;
		$type = $request->type;
		$description = $request->description;
		$restaurant_id = $this->restaurant->id;
		$last_editor_id = $this->user->id;
		$discount_by = $request->discount_by;
		$percent = null;
		$items = array();
		switch ($discount_by) {
			case 'discount_percent':
				$percent = $request->percent;
				if (!$percent) {
					return redirect()->back()->withInput()->withError('Discount percentage is missing');
				}
				$this->restaurant->discounts()->create(['name' => $name, 'branch_id' => $branch_id, 'type' => $type, 'description' => $description, 'restaurant_id' => $restaurant_id, 'last_editor_id' => $last_editor_id, 'discount_percent' => $percent]);
				break;
			case 'bonus_items':
				$ids = $request->item_ids;
				$qty = $request->qty;
				foreach ($qty as $key => $value) {
					if ($ids[$key] && $value) {
						if (array_key_exists($ids[$key], $items)) {
							$items[$ids[$key]] += (int) $value;
						} else {
							$items[$ids[$key]] = (int) $value;
						}
					}
				}
				if (count($items) == 0) {
					return redirect()->back()->withInput()->withError('Bonus items are missing');
				}
				$this->restaurant->discounts()->create(['name' => $name, 'branch_id' => $branch_id, 'type' => $type, 'description' => $description, 'restaurant_id' => $restaurant_id, 'last_editor_id' => $last_editor_id, 'bonus_items' => json_encode($items)]);
				break;
			default:
				return redirect()->back()->withInput()->withError('Something is wrong with provided information');
				break;
		}
		return redirect()->route('discount.index', $this->restaurant->slug)->withSuccess('Discount successfully created!');
	}

	public function delete ($restaurant_slug, $discount_id) {
		$discount = $this->restaurant->discounts->find($discount_id);
		if (!$discount) {
			return redirect()->route('discount.index', $this->restaurant->slug)->withError('Discount not found!');
		}
		$discount->delete();
		return redirect()->route('discount.index', $this->restaurant->slug)->withSuccess('Discount deleted!');
	}
	public function showFormEdit($restaurant_slug, $discount_id) {
		$discount = $this->restaurant->discounts->find($discount_id);
		if (!$discount) {
			return redirect()->route('discount.index', $this->restaurant->slug)->withError('Discount not found!');
		}
		return view('restaurant/discount/edit', ['restaurant' => $this->restaurant, 'discount' => $discount, 'bonus_items' => json_decode($discount->bonus_items, true)]);
	}

	public function update(Request $request, $restaurant_slug, $discount_id) {
		$discount = $this->restaurant->discounts->find($discount_id);
		if (!$discount) {
			return redirect()->route('discount.index', $this->restaurant->slug)->withError('Discount not found!');
		}
		$validator = Validator::make($request->all(), [
			'name'	=> 'required',
			'description'	=> 'required',
			'type'	=> 'required',
			'branch_id'	=> 'nullable',
			'discount_by'	=> 'required',
			'percent' => 'nullable|min:1,max:99'
		], [
			'required' => 'The :attribute field is missing',
			'min'	=> 'The :attribute must be at least :min',
			'max'	=> 'The :attribute must be at most :max'
		]);
		if ($validator->fails()) {
			return redirect()->back()->withInput()->withError('Something is wrong with provided information');
		}
		$name = $request->name;
		$branch_id = $request->branch_id;
		$type = $request->type;
		$description = $request->description;
		$restaurant_id = $this->restaurant->id;
		$last_editor_id = $this->user->id;
		$discount_by = $request->discount_by;
		$percent = null;
		$items = array();
		switch ($discount_by) {
			case 'discount_percent':
				$percent = $request->percent;
				if (!$percent) {
					return redirect()->back()->withInput()->withError('Discount percentage is missing');
				}
				$discount->update(['name' => $name, 'branch_id' => $branch_id, 'type' => $type, 'description' => $description, 'restaurant_id' => $restaurant_id, 'last_editor_id' => $last_editor_id, 'discount_percent' => $percent, 'bonus_items' => null]);
				break;
			case 'bonus_items':
				$ids = $request->item_ids;
				$qty = $request->qty;
				foreach ($qty as $key => $value) {
					if ($ids[$key] && $value) {
						if (array_key_exists($ids[$key], $items)) {
							$items[$ids[$key]] += (int) $value;
						} else {
							$items[$ids[$key]] = (int) $value;
						}
					}
				}
				if (count($items) == 0) {
					return redirect()->back()->withInput()->withError('Bonus items are missing');
				}
				$discount->update(['name' => $name, 'branch_id' => $branch_id, 'type' => $type, 'description' => $description, 'restaurant_id' => $restaurant_id, 'last_editor_id' => $last_editor_id, 'bonus_items' => json_encode($items), 'discount_percent' => null]);
				break;
			default:
				return redirect()->back()->withInput()->withError('Something is wrong with provided information');
				break;
		}
		return redirect()->route('discount.index', $this->restaurant->slug)->withSuccess('Discount successfully updated!');
	}
}
