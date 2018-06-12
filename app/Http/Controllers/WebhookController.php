<?php

namespace App\Http\Controllers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Restaurant;
use App\Customer;
use Facebook;
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
            $this->displaySenderAction($page_id, $senderId);
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
                    $response = $this->handleText($messageText);
                    if ($response->score < 0.5) {
                        $this->sendDefaultResponse($page_id, $senderId, "Bạn có thể gõ \"Menu\", \"Đặt bàn\", \"Chat với nhân viên\"");
                    }
                    else {
                        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
                        switch ($response->predicted_label) {
                            case 'booking':
                                $restaurant->bot->replyReservationPostback($senderId);
                                break;
                            case 'ask_menu':
                                $restaurant->bot->replyMenuOrderPostback($senderId);
                                break;
                            case 'greeting':
                                $this->sendDefaultResponse($page_id, $senderId, "Xin chào quý khách!");
                                break;
                            case 'goodbye':
                                $this->sendDefaultResponse($page_id, $senderId, "Xin chào tạm biệt quý khách và hẹn gặp lại!");
                                break;
                            case 'thank_you':
                                $this->sendDefaultResponse($page_id, $senderId, "Xin cảm ơn quý khách!");
                                break;
                            
                            default:
                                $this->sendDefaultResponse($page_id, $senderId, "Bạn có thể gõ \"Menu\", \"Đặt bàn\", \"Chat với nhân viên\"");
                                break;
                        }
                    }
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
        $this->displaySenderAction($page_id, $senderId);
        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
        $payload = $this->get_value_by_key($event, "postback")["payload"];
        switch ($payload) {
            case 'GET_STARTED_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                    $customer = $restaurant->customers->where('app_scoped_id', $senderId)->first();
                    $customer->updateInformation();
                    file_put_contents("php://stderr", 'Successfully created new customer!');
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                try {
                    $this->sendDefaultResponse($page_id, $senderId, "Chào mừng " . $customer->first_name . " " . $customer->last_name . " đến với " . $restaurant->name);
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                break;
            case 'BOOKING_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                    $customer = $restaurant->customers->where('app_scoped_id', $senderId)->first();
                    $customer->updateInformation();
                    file_put_contents("php://stderr", 'Successfully created new customer!');
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                if (!$restaurant->bot->booking) {
                    $this->sendTextMessage($page_id, $senderId, $restaurant->bot->msg_booking);
                }
                else try {
                    $restaurant->bot->replyReservationPostback($senderId);
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                break;
            case 'ORDER_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                    $customer = $restaurant->customers->where('app_scoped_id', $senderId)->first();
                    $customer->updateInformation();
                    file_put_contents("php://stderr", 'Successfully created new customer!');
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                if (!$restaurant->bot->order) {
                    $this->sendTextMessage($page_id, $senderId, $restaurant->bot->msg_order);
                } else try {
                    $restaurant->bot->replyOrderPostback($senderId);
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                break;
            case 'STAFF_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                    $customer = $restaurant->customers->where('app_scoped_id', $senderId)->first();
                    $customer->updateInformation();
                    file_put_contents("php://stderr", 'Successfully created new customer!');
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                $restaurant->bot->replyChatWithStaff($senderId);
                break;
            case 'CONTACT_PAYLOAD':
                try {
                    $restaurant->customers()->firstOrCreate(['app_scoped_id' => $senderId]);
                    $customer = $restaurant->customers->where('app_scoped_id', $senderId)->first();
                    $customer->updateInformation();
                    file_put_contents("php://stderr", 'Successfully created new customer!');
                } catch (Exception $e) {
                    file_put_contents("php://stderr", $e->getMessage());
                }
                $restaurant->bot->replyWithContact($senderId);
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

    private function sendDefaultResponse($page_id, $recipient_id, $message) {
        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
        if (!$restaurant) {
                file_put_contents("php://stderr", "Not found page id: " . $page_id);
                return;
        }
        // construct array of postback buttons
        $buttons = json_decode($restaurant->bot->generatePostbackButtonsForDefaultResponse($recipient_id), true);
        try {
          // Returns a `FacebookFacebookResponse` object
          $response = Facebook::post(
            '/me/messages?access_token='. $restaurant->bot->access_token,
            [
                "recipient" => [
                    "id" => $recipient_id
                ],
                "message" => [
                    "attachment" => [
                        "type" => "template",
                        "payload" => [
                            "template_type" => "generic",
                            "elements" => [
                                array(
                                    "title" => $message,
                                    "image_url" => $restaurant->background_url,
                                    "buttons" => $buttons
                                )
                            ]
                        ]
                    ]
                ]
            ],
            $restaurant->bot->access_token
          );
          file_put_contents("php://stderr", "Response: " . $response);
          file_put_contents("php://stderr", "Sent a generic template from " . $page_id . " to " . $recipientId);
        }
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        catch(Facebook\Exceptions\FacebookSDKException $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        catch(\Exception $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        return;
    }

    private function displaySenderAction($page_id, $recipient_id) {
        $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
        if (!$restaurant) {
                file_put_contents("php://stderr", "Not found page id: " . $page_id);
                return;
        }
        try {
          // Returns a `FacebookFacebookResponse` object
          $response = Facebook::post(
            '/me/messages?access_token='. $restaurant->bot->access_token,
            array(
                "recipient" => array("id" => $recipient_id),
                "sender_action" => "typing_on"
            ),
            $restaurant->bot->access_token
          );
          file_put_contents("php://stderr", "Response: " . $response);
          file_put_contents("php://stderr", "Sent a sender action from " . $page_id . " to " . $recipient_id);
        }
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        catch(Facebook\Exceptions\FacebookSDKException $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        catch(\Exception $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        return;
    }

    public function handleText($message) {
        $data = array("message" => $message);                                                                    
        $data_string = json_encode($data);                                                                                   
                                                                                                                             
        $ch = curl_init('https://booknow-nlp.herokuapp.com/predict');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                   
                                                                                                                             
        $result = curl_exec($ch);
        $json = json_decode($result);
        return $json;
    }
}
