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
			$bot->greeting = "Chào mừng {{user_full_name}} đến với " . $this->restaurant->name;
			$bot->welcome_message = "Chào mừng quý khách đến với " . $this->restaurant->name;
			$bot->default_response = "Bạn có thể gõ trực tiếp: \"Menu\", \"Đặt bàn\", \"Order\", \"Chat với nhân viên\", \"Số điện thoại\", \"Địa chỉ\", \"Giờ mở cửa\"";

			try {
			  // Returns a `FacebookFacebookResponse` object
			  $response = Facebook::post(
			    '/me/messenger_profile?access_token='. $bot->access_token,
			    array (
			    	"whitelisted_domains" => array("https://booknoww.herokuapp.com", route('homepage'))
			    ),
			    $bot->access_token
			  );
			} catch(Exception $e) {
				return redirect()->route('bot.index', $this->restaurant->slug)->withError($e->getMessage());
			}
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
									"type" => "web_url",
									"url" => route('customer.menu.view', ['restaurant_slug' => $this->restaurant->slug]),
									"title" => "Menu",
									"webview_height_ratio" => "full",
									"messenger_extensions" => "true",
									"webview_share_button" => "hide"
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
		      "fields" => array("persistent_menu","get_started", "greeting", "whitelisted_domains")
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
		$customer = $this->restaurant->customers()->firstOrCreate(['app_scoped_id' => "1855048967935590"]);
		$customer->updateInformation();
	}

	public function update (Request $request) {
		if (isset($request->activate)) {
			$activate = $request->activate ? 1 : 0;
			if (!$activate) {
				try {
				  // Returns a `FacebookFacebookResponse` object
				  $response = Facebook::delete(
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
					$this->restaurant->bot->active = 0;
					$this->restaurant->bot->save();
					
				}
			}
		}

		if (isset($request->activate_menu)) {
			$activate_menu = $request->activate_menu ? 1 : 0;
			$this->restaurant->bot->menu = $activate_menu;
			$this->restaurant->bot->msg_menu = $request->msg_menu;
			$this->restaurant->bot->save();
		}
		if (isset($request->activate_booking)) {
			$activate_booking = $request->activate_booking ? 1 : 0;
			$this->restaurant->bot->booking = $activate_booking;
			$this->restaurant->bot->msg_booking = $request->msg_booking;
			$this->restaurant->bot->save();
		}

		if (isset($request->activate_order)) {
			$activate_order = $request->activate_order ? 1 : 0;
			$this->restaurant->bot->order = $activate_order;
			$this->restaurant->bot->msg_order = $request->msg_order;
			$this->restaurant->bot->save();
		}

		if (isset($request->activate_chat_with_staff)) {
			$activate_chat_with_staff = $request->activate_chat_with_staff ? 1 : 0;
			$this->restaurant->bot->chat_with_staff = $activate_chat_with_staff;
			$this->restaurant->bot->msg_chat_with_staff = $request->msg_chat_with_staff;
			$this->restaurant->bot->save();
		}
		if (isset($request->address)) {
			$activate_address = $request->address ? 1 : 0;
			$this->restaurant->bot->address = $activate_address;
			$this->restaurant->bot->msg_address = $request->msg_address;
			$this->restaurant->bot->save();
		}
		if (isset($request->phone)) {
			$phone = $request->phone ? 1 : 0;
			$this->restaurant->bot->phone_number = $phone;
			$this->restaurant->bot->msg_phone_number = $request->msg_phone_number;
			$this->restaurant->bot->save();
		}
		if (isset($request->opening_hour)) {
			$opening_hour = $request->opening_hour ? 1 : 0;
			$this->restaurant->bot->opening_hour = $opening_hour;
			$this->restaurant->bot->msg_opening_hour = $request->msg_opening_hour;
			$this->restaurant->bot->save();
		}

		return redirect()->route('bot.index', $this->restaurant->slug)->withSuccess('Bot updated');
	}
}
