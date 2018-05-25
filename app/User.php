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

    public function update_pages(){
        try {
        // Returns a `FacebookFacebookResponse` object
        $response = Facebook::get(
          '/' . $this->provider_id . '/accounts',
          $this->user_access_token
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
        foreach ($graphNode as $key => $node) {
          $page = $node->asArray();
          $restaurant = Restaurant::where('fb_page_id', '=', $page['id'])->first();
          if (!$restaurant) {
            // if not exists in db
            if (in_array('ADMINISTER', $page['perms'])) {
                $page['page_profile_picture'] = $this->get_page_picture_url_from_page_id($page['id']);
                $page['url'] = 'https://www.facebook.com/' . $page['id'];
                  array_push($pages, $page);
            }
          }
          else {
            $admin = in_array('ADMINISTER', $page['perms']) ? 1 : 0;
            if ($this->restaurants->find($restaurant->id)) {
                $this->restaurants()->updateExistingPivot($restaurant->id, ['admin' => $admin]);
            } else {
                $this->restaurants()->attach($restaurant->id, ['admin' => $admin]);
            }
          }
        }
        $this->attributes['pages'] = json_encode($pages);
        $this->save();
        return $pages;
    }

    public static function get_page_picture_url_from_page_id($page_id) {
        return 'http://graph.facebook.com/' . $page_id . '/picture';
    }

    public static function get_profile_picture_url($facebook_user_id, $type="normal"){
      // $type = normal | square | null
      return 'http://graph.facebook.com/' . $facebook_user_id. '/picture?type=' . $type;
    }

    public function is_admin_of_page($page_id) {
        $admin = null;
        try {
        // Returns a `FacebookFacebookResponse` object
        $response = Facebook::get(
          '/' . $this->provider_id . '/accounts',
          $this->user_access_token
        );
        } catch(FacebookExceptionsFacebookResponseException $e) {
            return $admin;
        } catch(FacebookExceptionsFacebookSDKException $e) {
            return $admin;
        }
        $graphNode = $response->getGraphEdge();
        foreach ($graphNode as $key => $node) {
          $page = $node->asArray();
            if ($page_id == $page['id'] && in_array('ADMINISTER', $page['perms'])) {
                $admin = true;
                break;
            }
        }
        if ($admin) {
            $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
            if ($restaurant) {
                if ($this->restaurants->find($restaurant->id)) {
                    $this->restaurants()->updateExistingPivot($restaurant->id, ['admin' => 1]);
                } else {
                    $this->restaurants()->attach($restaurant->id, ['admin' => 1]);
                }
            }
        }
        return $admin;
    }

    public function has_role_in_page($page_id) {
        $staff = null;
        try {
        // Returns a `FacebookFacebookResponse` object
        $response = Facebook::get(
          '/' . $this->provider_id . '/accounts',
          $this->user_access_token
        );
        } catch(FacebookExceptionsFacebookResponseException $e) {
            return $staff;
        } catch(FacebookExceptionsFacebookSDKException $e) {
            return $staff;
        }
        $graphNode = $response->getGraphEdge();
        $admin = null;
        foreach ($graphNode as $key => $node) {
          $page = $node->asArray();
            if ($page_id == $page['id']) {
                if (in_array('ADMINISTER', $page['perms'])) {
                    $admin = true;
                }
                $staff = true;
                break;
            }
        }
        $admin = $admin ? 1 : 0;
        if ($staff) {
            $restaurant = Restaurant::where('fb_page_id', '=', $page_id)->first();
            if ($restaurant) {
                if ($this->restaurants->find($restaurant->id)) {
                    $this->restaurants()->updateExistingPivot($restaurant->id, ['admin' => $admin]);
                } else {
                    $this->restaurants()->attach($restaurant->id, ['admin' => $admin]);
                }
            }
        }
        return $staff;
    }
}
