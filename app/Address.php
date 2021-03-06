<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $appends = ['is_parent'];

    public function parent()
    {
        return $this->belongsTo('App\Address', 'parent_id')->first();
    }

    public function children()
    {
        return $this->hasMany('App\Address', 'parent_id');
    }

    public function getIsParentAttribute()
    {
        return $this->children()->get()->count() > 0;
    }
}
