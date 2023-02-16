<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $with = ['category'];
    protected $guarded = [];

    public static function booted()
    {
        static::creating(function (Product $product) {
            $product->slug = strtolower(Str::slug($product->name . '-' . Str::random(9)));
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
