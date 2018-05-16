<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Facebook;
class User extends Authenticatable
{
    const AUTH_REDIRECT = '/r';
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'provider',  'provider_id', 'user_access_token', 'pages'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The restaurants that belong to the user.
     */
    public function restaurants()
    {
        return $this->belongsToMany('App\Restaurant')->withPivot('admin');
    }

    public function hasPage($page_id) {
        $flag = null;
        if (!$page_id) return $flag;
        $pages = json_decode($this->pages, true);
        
        // check if current user can create restaurant by this page_id
        foreach ($pages as $page) {
            if ($page_id == $page['id']) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function get_page_access_token_from_page_id($page_id) {

        $pages = json_decode($this->pages, true);
        foreach ($pages as $page) {
            if ($page_id == $page['id']) {
                return $page['access_token'];
            }
        }
        return null;
    }

    public function get_page_from_page_id($page_id) {
        $pages = json_decode($this->pages, true);
        foreach ($pages as $page) {
            if ($page_id == $page['id']) {
                return $page;
            }
        }
        return null;
    }

    public function delete_page_by_id($page_id) {
        $pages = json_decode($this->pages, true);
        foreach($pages as $key => $page) {
            if(isset($page['id']) && $page['id'] == $page_id){
                //delete this particular object from the $array
                unset($pages[$key]);
            } 
        }
        $this->attributes['pages'] = json_encode($pages);
        $this->save();
    }

    public function update_pages(){
        $facebook_user_id = $this->provider_id;
        $user_access_token = $this->user_access_token;
        try {
        // Returns a `FacebookFacebookResponse` object
        $response = Facebook::get(
          '/' . $facebook_user_id . '/accounts',
          $user_access_token
        );
        } catch(FacebookExceptionsFacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(FacebookExceptionsFacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }
        $graphNode = $response->getGraphEdge();
        $pages = array();
        $page_id_array = array();
        foreach ($graphNode as $key => $node) {
          $page = $node->asArray();
          array_push($page_id_array, $page['id']);
          if ($restaurant = $this->check_exist_in_database($page['id']) === null) {
            // if not exists in db
            if (in_array('ADMINISTER', $page['perms'])) {
                $flag = 1;
                $page['page_profile_picture'] = $this->get_page_picture_url_from_page_id($page['id']);
                $page['url'] = 'https://www.facebook.com/' . $page['id'];
                  array_push($pages, $page);
            }
          }
          else {
            // exists in db
            $flag = 0;
            if (in_array('ADMINISTER', $page['perms'])) {
                $flag = 1;
            }
            $restaurant = Restaurant::where('fb_page_id', '=', $page['id'])->first();
            if ($this->check_if_user_has_restaurant_by_page_id($page['id'])) {
                // user has this restaurant already. update pivot
                $this->restaurants()->updateExistingPivot($restaurant->id, ['admin' => $flag]);
            }
            else {
                // add user to the list of admin of the restaurant
                $this->restaurants()->attach($restaurant->id, ['admin' => $flag]);
            }
          }
        }
        foreach ($this->restaurants as $restaurant) {
            // if a facebook user is removed from the list of a page's admins, he can not access the restaurant created from that page
            if (!in_array($restaurant->fb_page_id, $page_id_array)) {
                $this->restaurants()->detach($restaurant->id);
            }
        }
        $this->attributes['pages'] = json_encode($pages);
        $this->save();
        return $pages;
    }

    private function check_exist_in_database($page_id) {
        // check if page is in use
        return Restaurant::where('fb_page_id', '=', $page_id)->first();
    }

    public static function get_page_picture_url_from_page_id($page_id) {
        return 'http://graph.facebook.com/' . $page_id . '/picture';
    }

    public static function get_profile_picture_url($facebook_user_id, $type="normal"){
      // $type = normal | square | null
      return 'http://graph.facebook.com/' . $facebook_user_id. '/picture?type=' . $type;
    }

    private function check_if_user_has_restaurant_by_page_id($page_id) {
        return $this->restaurants->where('fb_page_id', '=', $page_id)->first();
    }
}
