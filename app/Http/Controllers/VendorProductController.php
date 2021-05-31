<?php

namespace App\Http\Controllers;

use App\Models\VendorProduct;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendor_products_list(Vendor $vendor) {
        return VendorProduct::where('vendor_id', $vendor->id)->with('product')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vendor $vendor)
    {
        $request->validate([
            'vendor_link' => 'required', 
            'color' => 'required',
            'size' => 'required',
            'price' => 'required',
            'name' => 'required'   
        ]);

        try{
            $vendor_product = new VendorProduct;
            $vendor_product->product_id = $request->name['code'];
            $vendor_product->color = $request->color;
            $vendor_product->size = $request->size;
            $vendor_product->price = $request->price;
            $vendor_product->vendor_id = $vendor->id;
            $vendor_product->vendor_link = $request->vendor_link;
            $vendor_product->save();
            return response()->json(['status'=>'success','message'=>'Vendor Product Added Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=> $e->getMessage() ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function show(VendorProduct $vendorProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorProduct $vendorProduct)
    {
        return $vendorProduct;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorProduct $vendorProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorProduct $vendorProduct)
    {
        try{
            $vendorProduct->delete();

            return response()->json(['status'=>'success','message'=>'Vendor Product Deleted Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }
}
