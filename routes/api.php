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

// Consulta de catálogo de libros
Route::get('/books', [BookController::class, 'index']);

// Préstamo de libro
Route::post('/loans', [LoanController::class, 'store']);

// Devolución de libro
Route::post('/returns/{loanId}', [LoanController::class, 'return']);
