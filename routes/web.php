<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintNotaController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/nota/{workOrder}/print', [PrintNotaController::class, 'print'])
        ->name('work-orders.print');
});
