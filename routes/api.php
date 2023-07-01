<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function(){
    Route::get("/user",[UserController::class,"Index"]);
    Route::get("/user/{d}/",[UserController::class,"show"]);
    
    Route::post("/user", [UserController::class,"Store"]);
});
/*
Route::prefix('v1')->group(function () {
    Route::get("/animal",[AnimalController::class,"Listar"]);
    Route::get("/animal/{d}/",[AnimalController::class,"ListarUno"]);
    Route::post("/animal",[AnimalController::class,"Insertar"]);
    Route::delete("/animal/{d}",[AnimalController::class,"Eliminar"]);
    Route::put("/animal/{d}",[AnimalController::class,"Modificar"]);
});
*/