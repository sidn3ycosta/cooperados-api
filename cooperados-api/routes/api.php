<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\V1\CooperadoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Rota de busca (deve vir ANTES do apiResource)
    Route::get('cooperados/search', [CooperadoController::class, 'search']);
    
    // Rota de health check
    Route::get('health', function () {
        return response()->json(['status' => 'ok']);
    });
    
    // Rota principal dos cooperados (deve vir DEPOIS das rotas específicas)
    Route::apiResource('cooperados', CooperadoController::class);
});
