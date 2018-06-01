<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Item extends Model
{
	use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'unit', 'price', 'category_id', 'image_url', 'item_url', 'public_id', 'ship', 'description'
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
                'source' => ['name', 'category_id']
            ]
        ];
    }

    /**
     * The category that belong to the restaurant.
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Order')->withPivot('qty', 'price');
    }

    public function money() {
        return number_format($this->price, 0, ',', '.');
    }
}
