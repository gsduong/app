<?php

namespace App\Http\Controllers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Restaurant;
class WebhookController extends Controller
{
	public function verify(Request $request) {
	    if ($request->input("hub_mode") === "subscribe"
	        && $request->input("hub_verify_token") === env("WEBHOOK_VERIFY_TOKEN")) {
	        return response($request->input("hub_challenge"), 200);
	    }
	}

	public function receive(Request $request) {
        $data = $request->all();
        $recipient_id = $data["entry"][0]["messaging"][0]["sender"]["id"];
        $page_id = $data["entry"][0]["messaging"][0]["recipient"]["id"];
        // echo $page_id;
        error_log($page_id);
        // $this->sendTextMessage($page_id, $recipient_id, "Hello");
	}


// private function sendTextMessage($page_id, $recipientId, $messageText)
//     {
//     	$page_access_token = Restaurant::where('fb_page_id', '=', $page_id)->first()->bot->access_token;
//         $messageData = [
//             "recipient" => [
//                 "id" => $recipientId
//             ],
//             "message" => [
//                 "text" => $messageText
//             ]
//         ];
//         $ch = curl_init('https://graph.facebook.com/v3.0/me/messages?access_token=' . $page_access_token);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_HEADER, false);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
//         curl_exec($ch);    
// 	}
}
