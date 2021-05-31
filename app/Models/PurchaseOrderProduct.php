<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderProduct extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'vendor_product_id', 'quantity'];

    /**
     * Get the vendor product associated with the purchase order.
     */
    public function vendorProduct()
    {
        return $this->hasOne(VendorProduct::class, 'id', 'vendor_product_id');
    }
}
