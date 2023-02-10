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

Route::get('/addproduct', [ProductController::class, 'addproduct']);
Route::get('/products', [ProductController::class, 'products']);



Route::get('/addslider', [SliderController::class, 'addslider']);
Route::get('/sliders', [SliderController::class, 'sliders']);
Route::get('/', [ClientController::class, 'home']);
Route::get('/shop', [ClientController::class, 'shop']);
Route::get('/cart', [ClientController::class, 'cart']);
Route::get('/checkout', [ClientController::class, 'checkout']);
Route::get('/login', [ClientController::class, 'login']);
Route::get('/signup', [ClientController::class, 'signup']);

Route::get('/orders', [ClientController::class, 'orders']);
