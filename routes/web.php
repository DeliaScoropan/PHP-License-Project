<?php

use Illuminate\Http\Requests;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome' ,[
       'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class,  'index'])->name('home');

Route::prefix('admin')->middleware(['auth','isAdmin'])->group(function () {

  Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class,  'index']);

  //Category routes
  Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
    Route::get('/category', 'index');
    Route::get('/category/create', 'create');
    Route::post('/category', 'store');
    Route::get('/category/{category}/edit', 'edit');
    Route::put('/category/{category}', 'update');
});

Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/create', 'create');
    Route::post('/products', 'store');
    Route::get('/products/{product}/edit', 'edit');
    Route::put('/products/{product}', 'update');
    Route::get('/products/{product_id}/delete', 'destroy');
    Route::get('product-image/{product_image_id}/delete', 'destroyImage');
});


Route::controller(App\Http\Controllers\Admin\ColorController::class)->group(function () {
    Route::get('/colors', 'index');
    Route::get('/colors/create', 'create');
    Route::post('/colors/create', 'store');
    Route::get('/colors/{color}/edit', 'edit');
    Route::put('/colors/{color_id}', 'update');
    Route::get('/colors/{color_id}/delete', 'destroy');
    
});

});