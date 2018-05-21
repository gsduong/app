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

                if ($data["object"] == 'page') {
                        // Iterate over each entry
                        // There may be multiple if batched
                        foreach ($data["entry"] as $key => $entry) {
                                $page_id = $entry["id"];
                                $time_of_event = $entry["time"];
                                foreach ($entry["messaging"] as $idx => $event) {
                                        if ($event["message"]) {
                                                $this->receivedMessage($event);
                                        }
                                        else if ($event["postback"]) {
                                                $this->receivedPostback($event);
                                        }
                                        else {
                                                error_log("Webhook received unknown messagingEvent: " . json_encode($event));
                                        }
                                }
                        }
                // Assume all went well.
                //
                // You must send back a 200, within 20 seconds, to let us know you've
                // successfully received the callback. Otherwise, the request will time out.
                        return 200;
                }

	}

        private function receivedMessage($event) {
                $senderID = $event["sender"]["id"];
                $recipientID = $event["recipient"]["id"];
                $page_id = $recipientID;
                $timeOfMessage = $event["timestamp"];
                $message = $event["message"];

                error_log("Received message for user " . $senderID . " and page " . $page_id . " at " . $timeOfMessage . " with message: " . json_encode($message));

                $isEcho = $message["is_echo"];
                $messageId = $message["mid"];
                $appId = $message["app_id"];
                $metadata = $message["metadata"];

                // You may get a text or attachment but not both
                $messageText = $message["text"];
                $messageAttachments = $message["attachments"];
                $quickReply = $message["quick_reply"];

                if ($isEcho) {
                // Just logging message echoes to console
                        error_log("Received echo for message " . $messageId . " and app " . $appId .  " with metadata " . $metadata);
                        return;
                } else if ($quickReply) {
                        $quickReplyPayload = $quickReply["payload"];
                        error_log("Quick reply for message " . $messageId . " with payload " . $quickReplyPayload);

                        sendTextMessage($page_id, $senderID, "Quick reply tapped");
                        return;
                }

                if ($messageText) {
                        sendTextMessage($page_id, $senderID, "We received: " . $messageText);
                        return;
                } else if ($messageAttachments) {
                        sendTextMessage($page_id, $senderID, "Message with attachment received!");
                        return;
                }
        }

        private function sendTextMessage($page_id, $recipientId, $messageText)
        {
        	$page_access_token = Restaurant::where('fb_page_id', '=', $page_id)->first()->bot->access_token;
                $messageData = [
                    "recipient" => [
                        "id" => $recipientId
                    ],
                    "message" => [
                        "text" => $messageText
                    ]
                ];
                $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $page_access_token);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
                curl_exec($ch);
                error_log($page_id . " replied " . $recipientID . " with message: " . $messageText);
        }

        private function receivedPostback($event) {

        }
}
