<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id', 'product_id', 'vendor_link', 'color', 'size', 'price'];

    /**
     * Get the product associated with the vendor product.
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Get the vendor that owns the vendor product.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
