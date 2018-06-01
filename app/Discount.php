<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'restaurant_id', 'branch_id', 'type', 'bonus_items', 'discount_percent', 'last_editor_id'
    ];

    /**
     * Get the restaurant that owns the discount.
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    /**
     * Get the branch that owns the discount.
     */
    public function branch()
    {
        return $this->belongsTo('App\ContactInfo', 'branch_id');
    }

    /**
     * Get the user that modified the discount.
     */
    public function last_editor()
    {
        return $this->belongsTo('App\User', 'last_editor_id');
    }

    public function get_type() {
        switch ($this->type) {
            case 'item':
                return 'Apply for individual items';
                break;
            
            case 'total':
                return 'Apply for the bill';
                break;
        }
    }

    public function bonus_items() {
        return json_decode($this->bonus_items, true);
    }
}
