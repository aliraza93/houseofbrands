<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.brands.index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brands_list() {
        return response()->json(Brand::get());
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'logo' => 'image|mimes:jpg,jpeg,png,svg|max:2084'
            'logo' => 'nullable'    
        ]);

        try{
            $brand = new Brand;
            $brand->name = $request->name;
            if($request->hasFile('logo')) {
                $brand->logo = $request->logo->store('brandsimages');
            }
            $brand->save();
            return response()->json(['status'=>'success','message'=>'Brand Added Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=> $e->getMessage() ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required',
            // 'logo' => 'image|mimes:jpg,jpeg,png,svg|max:2084'
            'logo' => 'nullable'    
        ]);

        try{
            $brand->name = $request->name;
            if($request->hasFile('logo')) {
                $brand->logo = $request->logo->store('brandsimages');
            }
            $brand->update();
            return response()->json(['status'=>'success','message'=>'Brand Updated Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=> $e->getMessage() ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try{
            $brand->delete();

            return response()->json(['status'=>'success','message'=>'Brand   Deleted Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }
}
