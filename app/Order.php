<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'restaurant_id', 'customer_phone', 'customer_address', 'customer_note',  'total', 'last_editor_id', 'creator_id', 'created_by_bot', 'status'
    ];

    public function items()
    {
        return $this->belongsToMany('App\Item', 'order_item', 'order_id', 'item_id')->withPivot('qty', 'price');
    }

    public function restaurant()
    {
        return $this->belongsToOne('App\Restaurant', 'restaurant_id');
    }
}
