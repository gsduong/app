<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'restaurant_id', 'customer_phone', 'customer_address', 'customer_note',  'total', 'last_editor_id', 'creator_id', 'created_by_bot', 'status', 'branch_id', 'customer_name', 'money'
    ];

    public function items()
    {
        return $this->belongsToMany('App\Item', 'order_item', 'order_id', 'item_id')->withPivot('qty', 'price');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function last_editor () {
        return User::find($this->last_editor_id);
    }

    public function getLabelClass(){
        $class = null;
        switch ($this->status) {
            case 'pending':
                $class = 'label bg-yellow';
                break;
            
            case 'confirmed':
                $class =  'label label-primary';
                break;
            case 'canceled':
                $class =  'label bg-red';
                break;
            case 'delivering':
                $class = 'label label-warning';
                break;
            case 'delivered':
                $class = 'label label-success';
                break;
            default:
                $class =  'label';
                break;
        }
        return $class;
    }
    public function creator () {
        if($this->creator_id) {
            return User::find($this->creator_id);
        }
    }
    public function customer () {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function money() {
        return (string) number_format((float) $this->total, 2);
    }

    public function branch()
    {
        return $this->belongsTo('App\ContactInfo', 'branch_id');
    }
}
