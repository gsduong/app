<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook;
class BotController extends Controller
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
		$bot = $this->restaurant->bot;
		return view('restaurant/bot/index', ['restaurant' => $this->restaurant, 'bot' => $bot]);
	}

	public function create($restaurant_slug) {
		if ($this->restaurant->bot) {

			return redirect()->route('bot.index', $this->restaurant->slug)->withError('Bot already exist!');
		}
		// if (!$this->restaurant->users->find($this->user->id)->pivot->admin) {
		// 	return view('restaurant/bot/index', ['restaurant' => $this->restaurant, 'bot' => $bot])->withError('Not authorized!');
		// }
		
		// subscribe for page
		try {
		  // Returns a `FacebookFacebookResponse` object
		  $response = Facebook::post(
		    '/'. $this->restaurant->fb_page_id . '/subscribed_apps',
		    array (
		      'access_token' => $this->restaurant->fb_page_access_token
		    ),
		    $this->restaurant->fb_page_access_token
		  );
		} catch(FacebookExceptionsFacebookResponseException $e) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
		} catch(FacebookExceptionsFacebookSDKException $e) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
		}
		$graphNode = $response->getGraphNode();
		if ($graphNode['success']) {
			$bot = $this->restaurant->bot()->create(['access_token' => $this->restaurant->fb_page_access_token]);
			$bot->save();
			return redirect()->route('bot.index', $this->restaurant->slug)->withSuccess('Bot successfully created!');
		}
	}
}
