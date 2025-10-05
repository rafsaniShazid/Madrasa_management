<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentPrintController;

Route::get('/', function () {
    return view('welcome');
});

// Student Print Routes
Route::get('/student/{student}/print', [StudentPrintController::class, 'print'])->name('student.print');
Route::get('/student/{student}/print-direct', [StudentPrintController::class, 'printDirect'])->name('student.print.direct');
Route::get('/student/{student}/print-data', [StudentPrintController::class, 'printData'])->name('student.print.data');
