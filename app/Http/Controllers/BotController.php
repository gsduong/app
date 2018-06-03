<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook;
use App\Bot;
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
		if (!$this->user->is_admin_of_page($this->restaurant->fb_page_id)) {
			return redirect()->back()->withError('Only admin can access this page');
		}
		$bot = $this->restaurant->bot;
		return view('restaurant/bot/index', ['restaurant' => $this->restaurant, 'bot' => $bot]);
	}

	public function create($restaurant_slug) {
		if (!$this->user->is_admin_of_page($this->restaurant->fb_page_id)) {
			return redirect()->back()->withError('Only admin can access this page');
		}
		if ($this->restaurant->bot) {

			return redirect()->route('bot.index', $this->restaurant->slug)->withError('Bot already exist!');
		}
		
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
		} catch(Exception $e) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
		}
		$graphNode = $response->getGraphNode();
		if ($graphNode['success']) {
			$bot = new Bot;
			$bot->access_token = $this->restaurant->fb_page_access_token;
			$bot->restaurant_id = $this->restaurant->id;
			$bot->welcome_message = "Chào mừng {{user_full_name}} đến với " . $bot->restaurant->name;
			$settings = array();
			$settings["menu"] = array("active" => 1, "default_response" => "Hiện tại menu của nhà hàng vẫn đang trong quá trình hoàn thiện. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
			$settings["address"] = array("active" => 1, "default_response" => "Hiện tại nhà hàng chưa cập nhật địa chỉ. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
			$settings["opening_hour"] = array("active" => 1, "default_response" => "Hiện tại nhà hàng chưa có giờ mở cửa cụ thể. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
			$settings["phone_number"] = array("active" => 1, "default_response" => "Hiện tại nhà hàng chưa cập nhật số điện thoại. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
			$settings["booking"] = array("active" => 1, "default_response" => "Hiện tại nhà hàng không nhận đặt bàn online. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
			$settings["chat_with_staff"] = array("active" => 1, "default_response" => "Hiện tại không có nhân viên nào trực tuyến. Mong quý khách vui lòng chờ đợi trong thời gian ngắn, nhà hàng sẽ liên lạc lại với quý khách! Xin cảm ơn!");
			$bot->settings = json_encode($settings);

			// set greeting message and get started button
			try {
			  // Returns a `FacebookFacebookResponse` object
			  $response = Facebook::post(
			    '/me/messenger_profile?access_token='. $bot->access_token,
			    array (
			      "get_started" => array("payload" => "GET_STARTED_PAYLOAD"),
			      "greeting" => array(array("locale" => "default", "text" => $bot->welcome_message)),
			      "persistent_menu" => array(
			      	array(
			      		"locale" => "default",
			      		"composer_input_disabled" => false, // disable = true means your bot can only be interacted with via the persistent menu, postbacks, buttons, and webviews
			      		"call_to_actions" => array(
							array(
								// Menu
								"title" => "Menu",
								"type" => "postback",
								"payload" => "MENU_PAYLOAD"
							),
							array(
								"title" => "Yêu cầu",
								"type" => "nested",
								"call_to_actions" => array(
									array(
										// Booking
										"title" => "Đặt bàn",
										"type" => "postback",
										"payload" => "BOOKING_PAYLOAD"
									),
									array(
										// Order
										"title" => "Order",
										"type" => "postback",
										"payload" => "ORDER_PAYLOAD"
									)
								)
							),
							array(
								// Information
								"title" => "Xem thêm",
								"type" => "nested",
								"call_to_actions" => array(
									array(
										// Booking
										"title" => "Contact",
										"type" => "postback",
										"payload" => "CONTACT_PAYLOAD"
									),
									array(
										// Order
										"title" => "Chat với nhân viên",
										"type" => "postback",
										"payload" => "STAFF_PAYLOAD"
									)
								)
							)
			      		)
			      	)
			      )
			    ),
			    $bot->access_token
			  );
			} catch(Exception $e) {
				return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
			}
			$graphNode = $response->getGraphNode();
			if ($graphNode["result"] == "success") {
				$bot->save();
				return redirect()->route('bot.index', $this->restaurant->slug)->withSuccess('Bot successfully created!');
			}
			else return redirect()->route('bot.index', $this->restaurant->slug)->withError('Failed to set properties for bot!');
		}
		else return redirect()->route('bot.index', $this->restaurant->slug)->withError('Failed to subscribe bot to page!');
	}

	public function delete($restaurant_slug) {
		if (!$this->user->is_admin_of_page($this->restaurant->fb_page_id)) {
			return redirect()->back()->withError('Only admin can access this page');
		}
		if (!$this->restaurant->bot) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError('Bot not exist!');
		}
		// delete properties
		try {
		  // Returns a `FacebookFacebookResponse` object
		  $response = Facebook::delete(
		    '/me/messenger_profile?access_token='. $this->restaurant->bot->access_token,
		    array (
		      "fields" => array("persistent_menu","get_started", "greeting")
		    ),
		    $this->restaurant->bot->access_token
		  );
		} catch(Exception $e) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
		}

		// unsubscribe for page
		$graphNode = $response->getGraphNode();
		if ($graphNode["result"] == "success") {
			try {
			  // Returns a `FacebookFacebookResponse` object
			  $response = Facebook::delete(
			    '/'. $this->restaurant->fb_page_id . '/subscribed_apps',
			    array (
			      'access_token' => $this->restaurant->bot->access_token
			    ),
			    $this->restaurant->bot->access_token
			  );
			} catch(Exception $e) {
				return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
			}
			$graphNode = $response->getGraphNode();
			if ($graphNode['success']) {
				$this->restaurant->bot->delete();
				return redirect()->route('bot.index', $this->restaurant->slug)->withSuccess('Bot successfully deleted!');
			}
			else return redirect()->route('bot.index', $this->restaurant->slug)->withError('Failed to unsubscribe the bot');
		}
		else return redirect()->route('bot.index', $this->restaurant->slug)->withError('Failed to delete properties of the bot');
	}

	public function test() {
		try {
		  // Returns a `FacebookFacebookResponse` object
		  $response = Facebook::get(
		    '/me/messenger_profile?fields=get_started,greeting,persistent_menu&access_token='. $this->restaurant->bot->access_token,
		    null,
		    $this->restaurant->bot->access_token
		  );
		} catch(Exception $e) {
			return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
		}
		dd($response);
	}
}
