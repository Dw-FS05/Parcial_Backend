<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanHistoryController;

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/loans/history', [LoanHistoryController::class, 'index']);

Route::get('/books', [BookController::class, 'index']);

Route::post('/loans', [LoanController::class, 'store']);

Route::post('/returns/{loanId}', [LoanController::class, 'return']);
