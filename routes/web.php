<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Cusomer Route
Route::get('/customer', 'CustomerController@index')->name('customer.index');
Route::post('/customer/store', 'CustomerController@store')->name('customer.store');
Route::get('/customer/getallcustomer', 'CustomerController@getAllCustomer')->name('customer.getallcustomer');
// Route::resource('/customer', 'CustomerController');


// Product Route
Route::resource('/product','ProductController');
Route::get('/product/fd','ProductController@fetch_data');