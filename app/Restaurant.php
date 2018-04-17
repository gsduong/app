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
        'name', 'fb_page_id', 'fb_page_access_token', 'creator_id'
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
                'source' => ['name', 'owner.id']
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
     * Get the user that owns the phone.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
}
