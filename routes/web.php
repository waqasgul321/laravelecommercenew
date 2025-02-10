<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Register authentication routes
Auth::routes();

// Define the home route
Route::get('/', [HomeController::class, 'index'])->name('home.index');


Route::get('/shop',[ShopController::class,'index'])->name('shop.index');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');

Route::post('/cart/store', [CartController::class, 'addToCart'])->name('cart.add');


Route::put('/cart/increase-qunatity/{rowId}',[CartController::class,'increase_item_quantity'])->name('cart.increase.qty');
Route::put('/cart/reduce-qunatity/{rowId}',[CartController::class,'reduce_item_quantity'])->name('cart.reduce.qty');
Route::delete('/cart/remove/{rowId}',[CartController::class,'remove_item_from_cart'])->name('cart.remove');
Route::delete('/cart/clear',[CartController::class,'empty_cart'])->name('cart.empty');






Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard',[UserController::class,'index'])->name('user.index');
});
Route::middleware(['auth',AuthAdmin::class])->group(function(){
Route::get('/admin',[AdminController::class, 'index'])->name('admin.index');

Route::get('/admin/brand',[AdminController::class,'brands'])->name('admin.brand');

Route::get('/admin/brand/add',[AdminController::class,'add_brand'])->name('admin.brand.add');

Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');

Route::get('/admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.brand.edit');

Route::put('/admin/brand/update',[AdminController::class,'update_brand'])->name('admin.brand.update');

Route::delete('/admin/brand/{id}/delete',[AdminController::class,'delete_brand'])->name('admin.brand.delete');

Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');

Route::get('/admin/category/add',[AdminController::class,'add_category'])->name('admin.category.add');

Route::post('/admin/category/store',[AdminController::class,'add_category_store'])->name('admin.category.store');

Route::get('/admin/category/{id}/edit',[AdminController::class,'edit_category'])->name('admin.category.edit');

Route::put('/admin/category/update',[AdminController::class,'update_category'])->name('admin.category.update');

Route::delete('/admin/category/{id}/delete',[AdminController::class,'delete_category'])->name('admin.category.delete');

Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');

Route::get('/admin/product/add',[AdminController::class,'add_product'])->name('admin.product.add');

Route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');

Route::get('product/{id}', [AdminController::class, 'showProduct'])->name('admin.product-detail');

Route::get('/admin/product/{id}/edit',[AdminController::class,'edit_product'])->name('admin.product.edit');

Route::put('/admin/product/update',[AdminController::class,'update_product'])->name('admin.product.update');

Route::delete('/admin/product/delete/{id}', [AdminController::class, 'delete_product'])->name('admin.product.delete');

Route::get('/shop/{product_slug}',[ShopController::class,'product_details'])->name("shop.product.details");


});