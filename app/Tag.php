<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];
    /**
     * Get all of the tools that are assigned this tag.
     */
    public function tools()
    {
        return $this->morphedByMany('App\Tool', 'taggable');
    }
}