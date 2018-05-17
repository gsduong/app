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
        'name', 'fb_page_id', 'fb_page_access_token', 'creator_id', 'avatar'
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
}
