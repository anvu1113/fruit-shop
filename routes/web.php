<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Category;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Item;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ListingOfferController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RealtorListingController;
use App\Http\Controllers\NotificationSeenController;
use Illuminate\Notifications\Events\NotificationSent;
use App\Http\Controllers\RealtorListingImageController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\RealtorListingAcceptOfferController;

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

Route::get('/', [IndexController::class, 'index'])->name('index');


Route::prefix('category')
->middleware('auth')
->group(function () {
  Route::get('', [Category::class, 'index'])->name('category.index');
  Route::get('/index', [Category::class, 'index'])->name('category.index');
  Route::get('/create', [Category::class, 'createEdit'])->name('category.create');
  Route::get('/edit/{category}', [Category::class, 'createEdit'])->name('category.edit');
  Route::post('/save', [Category::class, 'saveUpdate'])->name('category.save');
  Route::post('/update/{category}', [Category::class, 'saveUpdate'])->name('category.update');  
  Route::delete('delete/{category}', [Category::class, 'destroy'])->name('category.destroy');
});
Route::prefix('item')
->middleware('auth')
->group(function () {
  Route::get('', [Item::class, 'index'])->name('item.index');
  Route::get('/index', [Item::class, 'index'])->name('item.index');
  Route::get('/create', [Item::class, 'createEdit'])->name('item.create');
  Route::get('/edit/{item}', [Item::class, 'createEdit'])->name('item.edit');
  Route::post('/save', [Item::class, 'saveUpdate'])->name('item.save');
  Route::post('/update/{item}', [Item::class, 'saveUpdate'])->name('item.update');  
  Route::delete('delete/{item}', [Item::class, 'destroy'])->name('item.destroy');
});


Route::get('/hello', [IndexController::class, 'show'])
  ->middleware('auth');

Route::get('login', [AuthController::class, 'create'])
  ->name('login');
Route::post('login', [AuthController::class, 'store'])
  ->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])
  ->name('logout');

Route::resource('user-account', UserAccountController::class)
  ->only(['create', 'store']); 
