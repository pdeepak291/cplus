<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financialyears;

Route::get('/', [Financialyears::class, 'index'])->name('financialyear');