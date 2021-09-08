<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Models\Peminjam;


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

Route::get('/', function() {
    return view('auth/login');
});

Route::get('home', function(){
    return view('home');
});

//Minggu 3
Route::get('data_peminjam',function(){
    return view('peminjams/data_peminjam');
});

Route::get('lihat_data_peminjam', 'PeminjamController@lihat_data_peminjam');

Route::get('/index',function(){
    return view('index');
});


//Minggu 6
Route::get('/peminjam', 'PeminjamController@index')->name('peminjam.index');

Route::get('peminjam/create', 'PeminjamController@create')->name('peminjam.create');

Route::post('peminjam', 'PeminjamController@store')->name('peminjam.store');

//Minggu 7
Route::get('peminjam/edit/{id}', 'PeminjamController@edit')->name('peminjam.edit');
Route::put('peminjam/update/{id}', 'PeminjamController@update')->name('peminjam.update');
Route::post('peminjam/delete/{id}', 'PeminjamController@destroy')->name('peminjam.destroy');

Route::get('transaksi_peminjaman', 'TransaksiPeminjamanController@index')->name('transaksi_peminjaman.index');
Route::get('transaksi_peminjaman/create', 'TransaksiPeminjamanController@create')->name('transaksi_peminjaman.create');
Route::post('transaksi_peminjaman', 'TransaksiPeminjamanController@store')->name('transaksi_peminjaman.store');
Route::get('transaksi_peminjaman/detail_peminjam/{id}', 'TransaksiPeminjamanController@detail_peminjam')->name('transaksi_peminjaman.detail_peminjam');
Route::get('transaksi_peminjaman/detail_buku/{id}','TransaksiPeminjamanController@detail_buku')->name('transaksi_peminjaman.detail_buku');

Route::get('/peminjam/search','PeminjamController@search')->name('peminjams.search');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('user', 'UserController@index')->name('user.index');
Route::get('user/create', 'UserController@create')->name('user.create');
Route::post('user', 'UserController@store')->name('user.store');
Route::get('user/edit/{id}','UserController@edit')->name('user.edit');
Route::post('user/update/{id}', 'UserController@update')->name('user.update');
Route::post('user/delete/{id}', 'UserController@destroy')->name('user.destroy');

Route::get('buku', 'BukuController@index')->name('buku.index');
Route::get('buku/create', 'BukuController@create')->name('buku.create');
Route::post('buku', 'BukuController@store')->name('buku.store');
Route::get('buku/edit/{id}','BukuController@edit')->name('buku.edit');
Route::post('buku/update/{id}', 'BukuController@update')->name('buku.update');
Route::post('buku/delete/{id}', 'BukuController@destroy')->name('buku.destroy');