<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Get all of the products for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    // OLD VERSION (commented out)
    // public function products(): HasMany
    // {
    //     return $this->hasMany(Product::class);
    // }

    // Automatically generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // New products relationship
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
