<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    protected $appends = [
        'expand', 'title', 'label', 'value'
    ];

    public function getExpandAttribute()
    {
        return true;
    }

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getLabelAttribute()
    {
        return $this->name;
    }

    public function getValueAttribute()
    {
        return $this->id;
    }

    public function child()
    {
        return $this->hasMany(Menu::class, 'pid', 'id');
    }
}
