<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/user',function (){
//      return ('hello world');
// });
// Route::post('/user',function(){
//     return response()->json('post method is hited');
// });
// Route::delete('/user/{id}',function($id){
//     return response('Delete'. $id,200);
// });
// Route::put('/user/{id}',function($id){
//     return response('Put'. $id,200);
// });

// Route::get('test',function ()  {
//     p('working');
// });

// Route::get('/app',[UserController::class,'store']);

Route::get('/users/{flag}', [UserController::class,'index']);
Route::post('/user', [UserController::class,'store']);
Route::get('/user/{id}',[UserController::class,'show']);
Route::put('/user/{id}',[UserController::class,'update']);
Route::delete('/user/{id}',[UserController::class,'destroy']);

