<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

/* Route Dashboards */
Route::middleware('auth')->group(function () {
  // Main Page Route
  Route::get('/', [DashboardController::class, 'dashboardAnalytics'])->name('dashboard');

  /* Route Brands */
  Route::group(['prefix' => 'brands'], function () {
      Route::get('/', [BrandController::class, 'index'])->name('brands');
      Route::post('brand', [BrandController::class, 'store']);
      Route::delete('brand/{brand}', [BrandController::class, 'destroy']);
      Route::get('brands-list', [BrandController::class, 'brands_list']);
      Route::get('brand/{brand}/edit', [BrandController::class, 'edit']);
      Route::post('brand/{brand}/update', [BrandController::class, 'update']);
  });
  /* Route Brands */

  /* Route Vendors */
  Route::group(['prefix' => 'vendorss'], function () {
    Route::get('/', [VendorController::class, 'index'])->name('vendorss');
    Route::get('products/{vendor}', [VendorController::class, 'vendor_products']);
    Route::post('vendor', [VendorController::class, 'store']);
    Route::delete('vendor/{vendor}', [VendorController::class, 'destroy']);
    Route::get('vendor-list', [VendorController::class, 'vendors_list']);
    Route::get('categories-list', [VendorController::class, 'categories_list']);
    Route::get('vendor/{vendor}/edit', [VendorController::class, 'edit']);
    Route::post('vendor/{vendor}/update', [VendorController::class, 'update']);

    Route::get('vendor-products-list/{vendor}', [VendorProductController::class, 'vendor_products_list']);
    Route::get('products-list', [ProductController::class, 'products_list']);
    Route::post('product/{vendor}', [VendorProductController::class, 'store']);
    Route::get('vendor-product/{vendorProduct}/edit', [VendorProductController::class, 'edit']);
    Route::delete('vendor-product/{vendorProduct}', [VendorProductController::class, 'destroy']);
  });
/* Route Vendors */

});
/* Route Dashboards */

/* Route Pages */
Route::get('/error', 'MiscellaneousController@error')->name('error');

