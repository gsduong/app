<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'restaurant_id', 'settings', 'access_token', 'active', 'default_response', 'welcome_message', 'default_response_in_maintenance'
    ];

    /**
     * Get the restaurant that owns the bot
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function getActiveFieldsForDefaultResponse() {
        $settings = json_decode($this->settings, true);
        if (!$settings) {
             file_put_contents("php://stderr", 'Bot settings is null!');
            return null;
        }
        $fields = array();
        foreach ($settings as $key => $value) {
            if ($value["active"]) {
                switch ($key) {
                    case 'menu':
                        $fields[$key] = array("payload" => "MENU_PAYLOAD", "name" => "Menu");
                        break;
                    case 'order':
                        $fields[$key] = array("payload" => null, "name" => "Order đồ ăn");
                        break;
                    case 'booking':
                        $fields[$key] = array("payload" => "BOOKING_PAYLOAD", "name" => "Đặt bàn");
                        break;
                    case 'chat_with_staff':
                        $fields[$key] = array("payload" => "STAFF_PAYLOAD", "name" => "Chat với nhân viên");
                        break;
                    case 'address':
                        $fields[$key] = array("payload" => null, "name" => "Địa chỉ");
                        break;
                    case 'opening_hour':
                        $fields[$key] = array("payload" => null, "name" => "Giờ mở cửa");
                        break;
                    default:
                        break;
                }
            }
        }
        return $fields;
    }

    // public function getPostbackButtonsForDefaultResponse(){
    //     $fields = $this->getActiveFieldsForDefaultResponse();
    //     $buttons = array();
    //     foreach ($fields as $key => $value) {
    //         array_push($buttons, array("type" => "postback", "title" => $value["name"], "payload" => $value["payload"]));
    //     }
    //     return $buttons;
    // }
}
