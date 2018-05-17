<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category_image', 'restaurant_id'
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
                'source' => ['name', 'restaurant_id']
            ]
        ];
    }

    /**
     * Get the restaurant that owns the category.
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }
}
