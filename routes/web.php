<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserAccountController;
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
  Route::get('', [CategoryController::class, 'index'])->name('category.index');
  Route::get('/index', [CategoryController::class, 'index'])->name('category.index');
  Route::get('/create', [CategoryController::class, 'createEdit'])->name('category.create');
  Route::get('/edit/{category}', [CategoryController::class, 'createEdit'])->name('category.edit');
  Route::post('/save', [CategoryController::class, 'saveUpdate'])->name('category.save');
  Route::post('/update/{category}', [CategoryController::class, 'saveUpdate'])->name('category.update');  
  Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
});

Route::prefix('item')
->middleware('auth')
->group(function () {
  Route::get('', [ItemController::class, 'index'])->name('item.index');
  Route::get('/index', [ItemController::class, 'index'])->name('item.index');
  Route::get('/create', [ItemController::class, 'createEdit'])->name('item.create');
  Route::get('/edit/{item}', [ItemController::class, 'createEdit'])->name('item.edit');
  Route::post('/save', [ItemController::class, 'saveUpdate'])->name('item.save');
  Route::post('/update/{item}', [ItemController::class, 'saveUpdate'])->name('item.update');  
  Route::delete('delete/{item}', [ItemController::class, 'destroy'])->name('item.destroy');
});

Route::prefix('invoice')
->middleware('auth')
->group(function () {
  Route::get('', [InvoiceController::class, 'index'])->name('invoice.index');
  Route::get('/index', [InvoiceController::class, 'index'])->name('invoice.index');
  Route::get('/create', [InvoiceController::class, 'createEdit'])->name('invoice.create');
  Route::get('/edit/{invoice}', [InvoiceController::class, 'createEdit'])->name('invoice.edit');
  Route::post('/save', [InvoiceController::class, 'saveUpdate'])->name('invoice.save');
  Route::post('/update/{invoice}', [InvoiceController::class, 'saveUpdate'])->name('invoice.update');  
  Route::delete('delete/{invoice}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
  Route::post('/save-customer', [InvoiceController::class, 'saveCustomer'])->name('customer.save');
  Route::get('/review-pdf/{invoice}', [InvoiceController::class, 'reviewPdf'])->name('invoice.generate-pdf');
});



Route::get('login', [AuthController::class, 'create'])
  ->name('login');
Route::post('login', [AuthController::class, 'store'])
  ->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])
  ->name('logout');

Route::resource('user-account', UserAccountController::class)
  ->only(['create', 'store']); 
