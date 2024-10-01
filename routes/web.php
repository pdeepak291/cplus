<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financialyears;

Route::get('/', [Financialyears::class, 'index'])->name('home');
Route::get('/result', [Financialyears::class, 'result'])->name('result');
