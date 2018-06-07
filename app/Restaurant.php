<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Restaurant extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'fb_page_id', 'fb_page_access_token', 'creator_id', 'avatar', 'background_url'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name', 'fb_page_id']
            ]
        ];
    }

    /**
     * The users that belong to the restaurant.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'restaurant_user', 'restaurant_id', 'user_id')->withPivot('admin');
    }

    /**
     * Get the user that owns the restaurant.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function staffs()
    {
        $staffs = array();
        foreach ($this->users as $user) {
            if (!$user->pivot->admin) {
                array_push($staffs, $user);
            }
        }
        return $staffs;
    }

    public function admins()
    {
        $admins = array();
        foreach ($this->users as $user) {
            if ($user->pivot->admin) {
                array_push($admins, $user);
            }
        }
        return $admins;
    }

    /**
     * Get the contacts
     */
    public function contacts()
    {
        return $this->hasMany('App\ContactInfo', 'restaurant_id');
    }

    /**
     * Get the categories list for the blog post.
     */
    public function categories()
    {
        return $this->hasMany('App\Category', 'restaurant_id');
    }

    /**
     * Get the reservation
     */
    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'restaurant_id');
    }
    /**
     * The bot that belong to the restaurant.
     */
    public function bot()
    {
        return $this->hasOne('App\Bot', 'restaurant_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'restaurant_id');
    }

    public function update_users()
    {
        foreach ($this->users as $user) {
            if (!$user->has_role_in_page($this->fb_page_id)) {
                $this->users()->detach($user->id);
            }
        }
    }

    public function number_pending_reservations(){
        return $this->reservations->where('status', 'pending')->count();
    }

    public function pending_reservations() {
        return $this->reservations->where('status', 'pending')->sortByDesc('updated_at');
    }
    public function pending_orders() {
        return $this->orders->where('status', 'pending')->sortByDesc('updated_at');
    }

    /**
     * Get the discounts
     */
    public function discounts()
    {
        return $this->hasMany('App\Discount', 'restaurant_id');
    }
    /**
     * Get the customers
     */
    public function customers()
    {
        return $this->hasMany('App\Customer', 'restaurant_id');
    }

    /**
     * Get all of the items for the restaurant.
     */
    public function items()
    {
        return $this->hasManyThrough('App\Item', 'App\Category', 'restaurant_id', 'category_id', 'id', 'id');
    }
}
