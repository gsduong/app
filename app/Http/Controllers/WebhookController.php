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
                file_put_contents("php://stderr", "POST /webhook received: " . json_encode($data));
                $object = $this->get_value_by_key($data, 'object');
                if ($object == 'page') {
                        // Iterate over each entry
                        // There may be multiple if batched
                        foreach ($entry = $this->get_value_by_key($data, "entry") as $key => $entry) {
                                $page_id = $this->get_value_by_key($entry, "id");
                                $time_of_event = $this->get_value_by_key($entry,"time");
                                foreach ($this->get_value_by_key($entry,"messaging") as $idx => $event) {
                                        if ($this->get_value_by_key($event,"message")) {
                                                $this->receivedMessage($event);
                                        }
                                        else if ($this->get_value_by_key($event,"postback")) {
                                                $this->receivedPostback($event);
                                        }
                                        else {
                                                file_put_contents("php://stderr", "Webhook received unknown messagingEvent: " . json_encode($event));
                                        }
                                }
                        }
                        return response(200);
                }

	}

    private function receivedMessage($event) {
            $senderId = $this->get_value_by_key($event, "sender")["id"];
            $recipientId = $this->get_value_by_key($event, "recipient")["id"];
            $page_id = $recipientId;
            $timeOfMessage = $this->get_value_by_key($event,"timestamp");
            $message = $this->get_value_by_key($event,"message");

            file_put_contents("php://stderr", "Received message for user " . $senderId . " and page " . $page_id . " at " . $timeOfMessage . " with message: " . json_encode($message));

            $isEcho = $this->get_value_by_key($message,"is_echo");
            $messageId = $this->get_value_by_key($message,"mid");
            $appId = $this->get_value_by_key($message,"app_id");
            $metadata = $this->get_value_by_key($message,"metadata");

            // You may get a text or attachment but not both
            $messageText = $this->get_value_by_key($message,"text");
            $messageAttachments = $this->get_value_by_key($message,"attachments");
            $quickReply = $this->get_value_by_key($message,"quick_reply");

            if ($isEcho) {
            // Just logging message echoes to console
                    file_put_contents("php://stderr", "Received echo for message " . $messageId . " and app " . $appId .  " with metadata " . $metadata);
                    return;
            } else if ($quickReply) {
                    $quickReplyPayload = $this->get_value_by_key($quickReply,"payload");
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
    	$restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
            if (!$restaurant) {
                    file_put_contents("php://stderr", "Not found page id: " . $page_id);
                    return;
            }
            $page_access_token = $restaurant->bot->access_token;
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
            return;
    }

    private function receivedPostback($event) {
        $senderId = $this->get_value_by_key($event, "sender")["id"];
        $recipientId = $this->get_value_by_key($event, "recipient")["id"];
        $page_id = $recipientId;
        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
        $payload = $this->get_value_by_key($event, "postback")["payload"];
        switch ($payload) {
            case 'GET_STARTED_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                $this->sendDefaultResponse($page_id, $senderId, $restaurant->bot->welcome_message);
                break;
            
            default:
                $this->sendTextMessage($page_id, $senderId, $payload);
                break;
        }
        return;
    }

    private function get_value_by_key($array, $key) {
        return array_key_exists($key, $array) ? $array[$key] : null;
    }

    private function sendDefaultResponse($page_id, $recipientId, $message = null) {
        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
        $fields = $restaurant->bot->getActiveFieldsForDefaultResponse();
        if (!$fields) {
            $this->sendTextMessage($restaurant->fb_page_id, $recipientId, $restaurant->bot->default_response_in_maintenance);
        }
        $text = $message ? $message . " Bạn có thể gõ trực tiếp: " : $restaurant->bot->default_response;
        foreach ($fields as $key => $value) {
            $text = $text . "\"" . $value["name"] . "\", "
        }
        $text = rtrim($text,',');
        $text = $text . ".";
        try {
          // Returns a `FacebookFacebookResponse` object
          $response = Facebook::post(
            '/me/messages?access_token='. $restaurant->bot->access_token,
            array (
                "recipient" => array("id" => $recipientId),
                "message" => array(
                    "attachment" => array(
                        "type" => "template",
                        "payload" => array(
                            "template_type" => "generic",
                            "elements" => array(
                                array(
                                    "title" => $text,
                                ), 
                                array(
                                    "buttons" => $restaurant->bot->getPostbackButtonsForDefaultResponse()
                                )
                            )
                        )
                    )
                )
            ),
            $restaurant->bot->access_token
          );
        } catch(Exception $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        return;
    }
}
