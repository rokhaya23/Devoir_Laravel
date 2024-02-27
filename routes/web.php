<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/login',[\App\Http\Controllers\AuthController::class, 'login'])->name('users.login');
Route::post('/login',[\App\Http\Controllers\AuthController::class, 'doLogin']);
Route::post('/logout',[\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');



Route::resource('/produits', \App\Http\Controllers\ProduitController::class);
Route::resource('/users', \App\Http\Controllers\UtilisateurController::class);
Route::resource('/roles', \App\Http\Controllers\RoleController::class);
Route::post('/panier.store', [\App\Http\Controllers\PanierController::class, 'store'])->name('panier.store');



