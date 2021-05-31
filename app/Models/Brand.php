<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'logo'];

    /**
     * Get the categories for the brand.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get all of the produtcs for the brand.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, Category::class);
    }
}
