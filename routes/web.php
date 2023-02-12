<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;

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


/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php'; */

Route::get('/admin', [AdminController::class, 'admin']);
Route::get('/addcategory', [CategoryController::class, 'addcategory']);
Route::get('/categories', [CategoryController::class, 'categories']);
Route::post('/savecategory', [CategoryController::class, 'savecategory']);
Route::get('/edit_category/{id}', [CategoryController::class, 'edit_category']);
Route::post('/updatecategory/{id}', [CategoryController::class, 'updatecategory']);
Route::get('/delete_category/{id}', [CategoryController::class, 'delete_category']);

Route::get('/addproduct', [ProductController::class, 'addproduct']);
Route::post('/saveproduct', [ProductController::class, 'saveproduct']);
Route::get('/products', [ProductController::class, 'products']);
Route::post('/updateproduct/{id}', [ProductController::class, 'updateproduct']);
Route::get('/editproduct/{id}', [ProductController::class, 'editproduct']);
Route::get('/deleteproduct/{id}', [ProductController::class, 'deleteproduct']);
Route::get('/activate_product/{id}', [ProductController::class, 'activate_product']);
Route::get('/unactivate_product/{id}', [ProductController::class, 'unactivate_product']);



Route::get('/addslider', [SliderController::class, 'addslider']);
Route::get('/sliders', [SliderController::class, 'sliders']);
Route::post('/saveslider', [SliderController::class, 'saveslider']);
Route::post('/updateslider/{id}', [SliderController::class, 'updateslider']);
Route::get('/editslider/{id}', [SliderController::class, 'editslider']);
Route::get('/deleteslider/{id}', [SliderController::class, 'deleteslider']);
Route::get('/unactivateslider/{id}', [SliderController::class, 'unactivateslider']);
Route::get('/activateslider/{id}', [SliderController::class, 'activateslider']);


Route::get('/', [ClientController::class, 'home']);
Route::get('/shop', [ClientController::class, 'shop']);
Route::get('/cart', [ClientController::class, 'cart']);
Route::get('/checkout', [ClientController::class, 'checkout']);
Route::get('/login', [ClientController::class, 'login']);
Route::get('/signup', [ClientController::class, 'signup']);

Route::get('/orders', [ClientController::class, 'orders']);
