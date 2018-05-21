<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Reservation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'time', 'customer_id', 'creator_id', 'customer_phone', 'customer_name', 'customer_requirement', 'creator_id', 'last_editor_id', 'status', 'restaurant_id', 'adult', 'children', 'address_id', 'created_by_bot'
    ];

    /**
     * Get the restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function getLabelClass(){
    	$class = null;
    	switch ($this->status) {
    		case 'pending':
    			$class = 'label bg-yellow';
    			break;
    		
    		case 'confirmed':
    			$class =  'label bg-green';
    			break;
    		case 'cancelled':
    			$class =  'label bg-red';
    			break;
    		default:
    			$class =  'label';
    			break;
    	}
    	return $class;
    }

    public function getCreatorName(){
    	if(!$this->created_by_bot) {
    		return User::find($this->creator_id)->name;
    	}
    }
    public function getLastEditorName(){
		if($this->last_editor_id) {
			return User::find($this->last_editor_id)->name;
		}
    }
}
