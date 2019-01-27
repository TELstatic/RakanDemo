<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images'          => 'json',
        'categories'      => 'array',
        'categories_name' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }


}
