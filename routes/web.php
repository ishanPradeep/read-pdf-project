<?php

use App\Http\Controllers\PurchaseOrderController;
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


Route::get('/upload', [PurchaseOrderController::class, 'showForm']);
Route::post('/upload', [PurchaseOrderController::class, 'processPdf']);
