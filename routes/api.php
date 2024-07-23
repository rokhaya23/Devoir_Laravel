<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EShopMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommandeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//UTILISATEUR
Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',[AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);


Route::middleware(['auth:sanctum',EShopMiddleware::class])->group(function () {

    //PRODUITS
    Route::post('/produits', [ProduitController::class, 'store'])->middleware('role:Admin');
    Route::put('/produits/{produit}', [ProduitController::class, 'update'])->middleware('role:Admin');
    Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->middleware('role:Admin');
    Route::get('/produits/related', [ProduitController::class, 'related']);

    //CATEGORIES
    Route::get('/categories', [ProduitController::class, 'indexCategories'])->middleware('role:Admin');

    //COMMANDES
    Route::get('/commandes', [CommandeController::class, 'index']);
    Route::post('/commandes', [CommandeController::class, 'createOrder']);
    Route::get('/commandes/{id}', [CommandeController::class, 'show']);
    Route::put('/commandes/{id}', [CommandeController::class, 'update']);
    Route::delete('/commandes/{id}', [CommandeController::class, 'destroy']);
    Route::get('/commandes-validated', [CommandeController::class, 'fetchValidatedOrders']);
    Route::put('/commande/{id}', [CommandeController::class, 'updateOrderStatus']);

});

Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/{produit}', [ProduitController::class, 'show']);
Route::get('/produits/related', [ProduitController::class, 'related']);
Route::get('/random-products1', [ProduitController::class, 'getRandomProducts1']);
Route::get('/random-products2', [ProduitController::class, 'getRandomProducts2']);


Route::post('/register-and-order', [CommandeController::class, 'registerAndCreateOrder']);

