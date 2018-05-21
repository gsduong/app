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
                                                file_put_contents("php://stderr", "Webhook received unknown messagingEvent: " . json_encode($event));
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
                $senderId = $event["sender"]["id"];
                $recipientId = $event["recipient"]["id"];
                $page_id = $recipientId;
                $timeOfMessage = $event["timestamp"];
                $message = $event["message"];

                file_put_contents("php://stderr", "Received message for user " . $senderId . " and page " . $page_id . " at " . $timeOfMessage . " with message: " . json_encode($message));

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
                        file_put_contents("php://stderr", "Received echo for message " . $messageId . " and app " . $appId .  " with metadata " . $metadata);
                        return;
                } else if ($quickReply) {
                        $quickReplyPayload = $quickReply["payload"];
                        file_put_contents("php://stderr", "Quick reply for message " . $messageId . " with payload " . $quickReplyPayload);

                        $this->sendTextMessage($page_id, $senderId, "Quick reply tapped");
                        return;
                }

                if ($messageText) {
                        $this->sendTextMessage($page_id, $senderId, "We received: " . $messageText);
                        return;
                } else if ($messageAttachments) {
                        $this->sendTextMessage($page_id, $senderId, "Message with attachment received!");
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
                file_put_contents("php://stderr", $page_id . " replied " . $recipientId . " with message: " . $messageText);
        }

        private function receivedPostback($event) {

        }
}
