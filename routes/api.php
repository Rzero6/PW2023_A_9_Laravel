<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::post("/admin/login", [AuthController::class, "loginAdmin"]);
Route::post("/admin/register", [AuthController::class, "registerAdmin"]);

Route::middleware('auth:api')->group(function () {

    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang', [CabangController::class, 'store']);
    Route::get('/cabang/{id}', [CabangController::class, 'show']);
    Route::put('/cabang/{id}', [CabangController::class, 'update']);
    Route::delete('/cabang/{id}', [CabangController::class, 'destroy']);

    Route::get('/mobil', [MobilController::class, 'index']);
    Route::post('/mobil', [MobilController::class, 'store']);
    Route::get('/mobil/{id}', [MobilController::class, 'show']);
    Route::patch('/mobil/{id}', [MobilController::class, 'update']);
    Route::delete('/mobil/{id}', [MobilController::class, 'destroy']);

    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
    Route::get('/transaksi/{status}', [TransaksiController::class, 'showTransaksiByUserAndStatus']);
    Route::patch('/transaksi/{id}', [TransaksiController::class, 'updateStatus']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

    Route::get('/review', [ReviewController::class, 'index']);
    Route::post('/review', [ReviewController::class, 'store']);
    Route::get('/review/{id}', [ReviewController::class, 'show']);
    Route::get('/review/mobil/{id}', [ReviewController::class, 'showByMobil']);
    Route::patch('/review/{id}', [ReviewController::class, 'update']);
    Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
});
