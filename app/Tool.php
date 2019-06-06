<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'title',
        'link',
        'description'
    ];

    /**
     * Get all of the tags for the tool.
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function getTagsAttribute($value)
    {
        return $this->tags()->pluck('name');
    }
}