<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoapController;

Route::get('/login',function(){
    return view('auth.login');
    return view('index');
})->name('login');

Auth::routes(['register' => false]);
Route::resource('/usuario', App\Http\Controllers\UserController::class)->middleware('auth');
Route::resource('/analisis', App\Http\Controllers\AnalisisController::class)->middleware('auth');
Route::post('/importar', [App\Http\Controllers\ExcelController::class, 'importar'])->name('import');
Route::get('/exportar', [App\Http\Controllers\ExcelController::class, 'exportar'])->name('export');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});



Route::get('/soap-service/wsdl', [SoapController::class, 'getWsdl']);

Route::any('/soap-service', function () {
    try {
        $wsdl = url('/soap-service/wsdl');
        $options = [
            'trace' => 1,
            'encoding' => 'UTF-8',
        ];

        $server = new \SoapServer($wsdl, $options);
        $server->setClass(SoapController::class);
        $server->handle();
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
