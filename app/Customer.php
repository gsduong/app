<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Facebook;
class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'app_scoped_id', 'restaurant_id', 'profile_pic', 'first_name', 'last_name', 'phone', 'address', 'email'
    ];
    /**
     * Get the restaurant that owns the customer.
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function updateInformation() {
        // update first_name, last_name, profile_pic
        $response = null;
        try {
          // Returns a `FacebookFacebookResponse` object
          $response = Facebook::get(
            $this->app_scoped_id . '?fields=first_name,last_name,profile_pic&access_token=' . $this->restaurant->fb_page_access_token,
            null,
            $this->restaurant->fb_page_access_token
          );
        } catch(Exception $e) {
            return;
        }
        $result = $response->getGraphObject()->asArray();
        if ($result) {
            if(array_key_exists("first_name", $result)) {
                $this->attributes['first_name'] = $result["first_name"];
            }
            if(array_key_exists("last_name", $result)) {
                $this->attributes['last_name'] = $result["last_name"];
            }
            if(array_key_exists("profile_pic", $result)) {
                $this->attributes['profile_pic'] = $result["profile_pic"];
            }
            $this->save();
        }
    }

    public function getName(){
      $name = "";
      if (!$this->attributes['name']) {
        return $name . $this->attributes['first_name'] . " " . $this->attributes['last_name'];
      }
      else return $this->attributes['name'];
    }
}
