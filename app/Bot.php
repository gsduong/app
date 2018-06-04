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
        'restaurant_id', 'settings', 'access_token', 'active', 'default_response', 'welcome_message', 'default_response_in_maintenance', 'menu', 'order', 'address', 'opening_hour', 'phone_number', 'booking', 'chat_with_staff', 'msg_menu', 'msg_address', 'msg_order', 'msg_opening_hour', 'msg_phone_number', 'msg_booking', 'msg_chat_with_staff'
    ];

    /**
     * Get the restaurant that owns the bot
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }
}
