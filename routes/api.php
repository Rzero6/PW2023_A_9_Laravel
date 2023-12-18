<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
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


Route::get('/cabang', [CabangController::class, 'index']);
Route::post('/cabang', [CabangController::class, 'store']);
Route::get('/cabang/{id}', [CabangController::class, 'show']);
Route::put('/cabang/{id}', [CabangController::class, 'update']);
Route::delete('/cabang/{id}', [CabangController::class, 'destroy']);
