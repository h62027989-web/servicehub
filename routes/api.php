<?php
use Illuminate\Support\Facades\Route;use App\Http\Controllers\Api\AuthApiController;use App\Http\Controllers\Api\ServiceApiController;use App\Http\Controllers\Api\BookingApiController;
Route::post('/register',[AuthApiController::class,'register']);Route::post('/login',[AuthApiController::class,'login']);Route::get('/services',[ServiceApiController::class,'index']);Route::get('/services/{service}',[ServiceApiController::class,'show']);
Route::middleware('auth:sanctum')->group(function(){Route::post('/logout',[AuthApiController::class,'logout']);Route::get('/bookings',[BookingApiController::class,'index']);Route::post('/bookings',[BookingApiController::class,'store']);Route::get('/bookings/{booking}',[BookingApiController::class,'show']);});
