<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\FollowsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function(){
    Route::get('/validate',[UserController::class,"ValidateToken"])->middleware('auth:api');
    Route::get('/logout', [UserController::class,"logout"])->middleware('auth:api');

    Route::get("/user",[UserController::class,"List"]);
    Route::get("/user/{d}/",[UserController::class,"ListOne"]);
    Route::put("/user/{d}/",[UserController::class,"edit"]);
    Route::post("/user", [UserController::class,"Register"]);
    Route::delete("/user/{d}/",[UserController::class,"delete"]);

    Route::get("/country", [CountryController::class,"List"]);
    Route::get("/country/{d}/",[CountryController::class,"ListOne"]);
    Route::post("/country", [CountryController::class,"Create"]);

    Route::get("/interest", [InterestController::class, "List"]);

    Route::get("/likes",[LikesController::class,"List"]);
    Route::get("/likes/{d}/",[LikesController::class,"ListOne"]);
    Route::get("/likes/user/{d}/",[LikesController::class,"ListUserInterest"]);
    Route::get("/likes/interest/{d}/",[LikesController::class,"ListInterestUsers"]);
    Route::post("/likes", [LikesController::class,"Create"]);
    Route::delete("/likes/{d}", [LikesController::class,"delete"]);

    Route::get("/followers/{d}",[FollowsController::class,"ListFollowers"]);
    Route::get("/followeds/{d}",[FollowsController::class,"ListFolloweds"]);
    Route::get("/friends/{d}",[FollowsController::class,"ListFriends"]);
    Route::post("/follow",[FollowsController::class,"Follow"]);
    Route::post("/unfollow",[FollowsController::class,"UnFollow"]);
    Route::post("/friends",[FollowsController::class,"MakeFriend"]);
    Route::post("/friends/unfriend",[FollowsController::class,"UnFriend"]);
});
