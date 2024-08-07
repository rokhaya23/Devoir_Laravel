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



// Route::get('/login',[\App\Http\Controllers\AuthController::class, 'login'])->name('users.login');
// Route::post('/login',[\App\Http\Controllers\AuthController::class, 'doLogin']);
// Route::post('/logout',[\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');



// Route::resource('/produits', \App\Http\Controllers\ProduitController::class);
// Route::resource('/users', \App\Http\Controllers\UtilisateurController::class);
// Route::resource('/roles', \App\Http\Controllers\RoleController::class);
// Route::resource('/clients', \App\Http\Controllers\ClientController::class );
// Route::resource('/commande', \App\Http\Controllers\CommandeController::class );

// Route::get('/orders/customer/{id}', [\App\Http\Controllers\CommandeController::class, 'getCustomerDetails']);
// Route::get('/orders/product/{id}', [\App\Http\Controllers\CommandeController::class, 'getProductDetails']);


// Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Route::get('/generate-pdf', [\App\Http\Controllers\PDFController::class, 'generatePDF'])->name('clients.pdf');

// Route::get('/export-produits', [\App\Http\Controllers\ExcelController::class, 'export'])->name('export.excel');

// Route::get('/valider-commande', [\App\Http\Controllers\ValiderCommandeController::class, 'List'])->name('valider-commande');
// Route::get('/valider-commande/{commande}', [\App\Http\Controllers\ValiderCommandeController::class, 'showList'])->name('status');
// Route::patch('/valider-commande/{commande}/update-status', [\App\Http\Controllers\ValiderCommandeController::class, 'updateStatus'])->name('updateStatus');
