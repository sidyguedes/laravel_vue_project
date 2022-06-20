<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Auth\AuthApiController;


Route::get('/user', [AuthApiController::class, 'getAuthenticatedUser']);
Route::post('/auth-refresh', [AuthApiController::class, 'refreshToken']);
Route::post('/auth', [AuthApiController::class, 'authenticate']);

Route::group(['prefix' => 'pedidos'], function() {
    Route::controller(PedidoController::class)->group(function () {

        Route::post('/search', 'search');
        Route::delete('/{id}', 'destroy');
        Route::put('/{id}', 'update');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::get('/', 'index');
        
    });
});

Route::group(['prefix' => 'produtos'], function() {
    Route::controller(ProdutoController::class)->group(function () {

        Route::post('/search', 'search');
        Route::delete('/{id}', 'destroy');
        Route::put('/{id}', 'update');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::get('/', 'index');
        
    });
});

Route::group(['prefix' => 'clientes'], function() {
    Route::controller(ClienteController::class)->group(function () {

        Route::post('/search', 'search');
        Route::delete('/{id}', 'destroy');
        Route::put('/{id}', 'update');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::get('/', 'index');
        
    });
});
