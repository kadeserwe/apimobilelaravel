<?php

//use App\Models\Bailleurs;
use App\Http\Controllers\Api\BailleurController;
use App\Http\Controllers\Api\ProcedureController;
use App\Http\Controllers\Api\DemandeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Route::get('/bailleurs', [BailleurController::class, 'index']);
// Route::get('/bailleurs/{id}', [BailleurController::class, 'show']);
// Route::post('/bailleurs', [BailleurController::class, 'store']);
// Route::put('/bailleurs/{id}', [BailleurController::class, 'update']);
// Route::delete('/bailleurs/{id}', [BailleurController::class, 'destroy']);
//Route::resource('bailleurs', BailleurController::class);
//Route::apiResource('bailleurs', BailleurController::class);


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
//Route::get('procedure', ProcedureController::class, 'index');
Route::resource('bailleurs', BailleurController::class);
Route::resource('procedure', ProcedureController::class);
   Route::resource('demande', DemandeController::class);
   

Route::middleware('auth:api')->group(function () {
   // Route::resource('posts', PostController::class);
   

   //Route::resource('procedure', ProcedureController::class);
  // Route::resource('procedure', ProcedureController::class);
});

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/