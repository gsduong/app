<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Facebook;
use App\Restaurant;
use App\Customer;
class Bot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'restaurant_id', 'settings', 'access_token', 'active', 'default_response', 'welcome_message', 'default_response_in_maintenance', 'menu', 'order', 'address', 'opening_hour', 'phone_number', 'booking', 'chat_with_staff', 'msg_menu', 'msg_address', 'msg_order', 'msg_opening_hour', 'msg_phone_number', 'msg_booking', 'msg_chat_with_staff', 'greeting'
    ];

    /**
     * Get the restaurant that owns the bot
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function generatePostbackButtonsForDefaultResponse($recipient_id){
        $buttons = array();
        if ($this->menu) {
            array_push($buttons, array(
                "type" => "web_url",
                "url" => route('customer.menu.view', ['restaurant_slug' => $this->restaurant->slug]),
                "title" => "Menu",
                "webview_height_ratio" => "full",
                "messenger_extensions" => "true",
                "webview_share_button" => "hide"
            ));
        }
        if ($this->booking) {
            array_push($buttons, $this->getURLBookingButton($this->restaurant->fb_page_id, $recipient_id));
        }
        if ($this->chat_with_staff) {
            array_push($buttons, array(
                "title" => "Chat với nhân viên",
                "type" => "postback",
                "payload" => "STAFF_PAYLOAD"
            ));
        }
        return json_encode($buttons);
    }

    private function getURLBookingButton ($page_id, $recipient_id) {
        return array(
            "type" => "web_url",
            "url" => route('customer.reservation', ['restaurant_slug' => $this->restaurant->slug, 'psid' => $recipient_id]),
            "title" => "Đặt bàn",
            "webview_height_ratio" => "full",
            "messenger_extensions" => "true",
            "webview_share_button" => "hide"
        );
    }

    public function sendTextMessage($recipient_id, $message)
    {
        $restaurant = $this->restaurant;
        $page_id = $restaurant->fb_page_id;
        $page_access_token = $this->access_token;
        $message_data = [
            "recipient" => [
                "id" => $recipient_id
            ],
            "message" => [
                "text" => $message
            ]
        ];
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $page_access_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message_data));
        curl_exec($ch);
        file_put_contents("php://stderr", $page_id . " replied " . $recipient_id . " with message: " . $message);
        return;
    }

    public function displaySenderAction($recipient_id) {
        $restaurant = $this->restaurant;
        $page_id = $restaurant->fb_page_id;
        $page_access_token = $this->access_token;
        $response = Facebook::post(
        '/me/messages?access_token='. $page_access_token,
        array(
            "recipient" => array("id" => $recipient_id),
            "sender_action" => "typing_on"
        ),
        $page_access_token
        );
        return;
    } 

    public function replyReservation(Reservation $reservation, Customer $customer) {
        $page_id = $reservation->restaurant->fb_page_id;
        $status = $reservation->status;
        $page_access_token = $this->access_token;
        $button = array();
        $message = "";
        switch ($status) {
            case 'pending':
                $message = "Yêu cầu đặt bàn đã được " . $reservation->restaurant->name . " tiếp nhận";
                break;
            case 'confirmed':
                $message = "Đặt bàn thành công";
                break;
            case 'canceled':
                $message = "Huỷ đặt bàn thành công";
                break;
            default:
                $message = "Cảm ơn quý khách đã sử dụng dịch vụ của " . $reservation->restaurant->name;
                break;
        }
        $template_btn = [
            "recipient" => [
                "id" => $customer->app_scoped_id
            ], 
            "message" => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            array(
                                "title" => $message,
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => route('customer.reservation.review', ['restaurant_slug' => $reservation->restaurant->slug, 'reservation_id' => $reservation->id]),
                                        "title" => "Xem lại",
                                        "webview_height_ratio" => "full",
                                        "messenger_extensions" => "true",
                                        "webview_share_button" => "hide"
                                    ]
                                ]
                            )
                        ]
                    ]
                ]
            ]
        ];
        $response = Facebook::post(
            '/me/messages?access_token='. $page_access_token,
            $template_btn,
            $page_access_token
        );

    }

    public function replyReservationPostback($recipient_id) {
        $page_id = $this->restaurant->fb_page_id;
        $page_access_token = $this->access_token;
        $button = array();
        $message = "Mời bạn đặt bàn";
        $template_btn = [
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
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => route('customer.reservation', ['restaurant_slug' => $this->restaurant->slug, 'psid' => $recipient_id]),
                                        "title" => "Đặt bàn",
                                        "webview_height_ratio" => "full",
                                        "messenger_extensions" => "true",
                                        "webview_share_button" => "hide"
                                    ]
                                ]
                            )
                        ]
                    ]
                ]
            ]
        ];
        $response = Facebook::post(
            '/me/messages?access_token='. $page_access_token,
            $template_btn,
            $page_access_token
        );
    }

    public function replyMenuOrderPostback($recipient_id) {
        $page_id = $this->restaurant->fb_page_id;
        $page_access_token = $this->access_token;
        $button = array();
        $message = "Mời bạn xem menu và đặt đồ ăn online";
        $template_btn = [
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
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => route('customer.show-form-create-order', ['restaurant_slug' => $this->restaurant->slug, 'customer_psid' => $recipient_id]),
                                        "title" => "Menu",
                                        "webview_height_ratio" => "full",
                                        "messenger_extensions" => "true",
                                        "webview_share_button" => "hide"
                                    ]
                                ]
                            )
                        ]
                    ]
                ]
            ]
        ];
        $response = Facebook::post(
            '/me/messages?access_token='. $page_access_token,
            $template_btn,
            $page_access_token
        );
    }

    public function replyOrderPostback($recipient_id) {
        $page_id = $this->restaurant->fb_page_id;
        $page_access_token = $this->access_token;
        $button = array();
        $message = "Mời bạn gọi đồ";
        $template_btn = [
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
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => route('customer.show-form-create-order', ['restaurant_slug' => $this->restaurant->slug, 'customer_psid' => $recipient_id]),
                                        "title" => "Order Now",
                                        "webview_height_ratio" => "full",
                                        "messenger_extensions" => "true",
                                        "webview_share_button" => "hide"
                                    ]
                                ]
                            )
                        ]
                    ]
                ]
            ]
        ];
        $response = Facebook::post(
            '/me/messages?access_token='. $page_access_token,
            $template_btn,
            $page_access_token
        );
    }

    public function replyChatWithStaff($recipient_id) {
        if ($this->chat_with_staff) {
            $this->sendTextMessage($recipient_id, "Quý khách vui lòng chờ trong giây lát. Nhân viên của chúng tôi sẽ liên hệ với quý khách trong thời gian sớm nhất. Xin cảm ơn!");
        }
        else {
            $this->sendTextMessage($recipient_id, $this->msg_chat_with_staff);
        }
    }

    public function replyWithContact($recipient_id) {
        if ($this->restaurant->contacts->count() > 0 ) {
            foreach ($this->restaurant->contacts as $key => $contact) {
                $this->sendTextMessage($recipient_id, $contact->toString());
            }
        }
        else {
            $this->sendTextMessage($recipient_id, "Hiện tại nhà hàng chưa cập nhật thông tin liên hệ. Xin cảm ơn quý khách!");
        }
    }

    public function replyAddress($recipient_id) {
        if ($this->address) {
            foreach ($this->restaurant->contacts as $key => $contact) {
                $this->sendTextMessage($recipient_id, $contact->toString());
            }
        }
        else {
            $this->sendTextMessage($recipient_id, $this->msg_address);
        }
    }
    public function replyPhone($recipient_id) {
        if ($this->phone_number) {
            foreach ($this->restaurant->contacts as $key => $contact) {
                $this->sendTextMessage($recipient_id, $contact->toString());
            }
        }
        else {
            $this->sendTextMessage($recipient_id, $this->msg_phone_number);
        }
    }
    public function replyHours($recipient_id) {
        if ($this->opening_hour) {
            foreach ($this->restaurant->contacts as $key => $contact) {
                $this->sendTextMessage($recipient_id, $contact->name . ": " . $contact->opening_time . " - " . $contact->closing_time);
            }
        }
        else {
            $this->sendTextMessage($recipient_id, $this->msg_opening_hour);
        }
    }
}
