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
Route::post("/user", [UserController::class,"Register"]);
});


Route::prefix('v1')->middleware('auth:api')->group(function(){

    Route::get('/validate',[UserController::class,"ValidateToken"]);
    Route::get('/logout', [UserController::class,"logout"]);

    Route::get("/user/{d}/",[UserController::class,"ListOne"]);
    Route::get("/user/post/{d}/",[UserController::class,"ListOnePost"]);
    Route::get("/user/profile/{d}/",[UserController::class,"ListOneProfile"]);
    Route::put("/user",[UserController::class,"edit"]);
    Route::post("/user/2", [UserController::class,"Register2"]);
    Route::delete("/user",[UserController::class,"delete"]);
 

    Route::get("/country", [CountryController::class,"List"]);
    Route::get("/country/{d}/",[CountryController::class,"ListOne"]);
    Route::post("/country", [CountryController::class,"Create"]);

    Route::get("/interest", [InterestController::class, "List"]);
    Route::get("/interest/{d}", [InterestController::class, "ListOne"]);

    Route::get("/likes/user/{d}", [LikesController::class, "ListOtherUserInterest"]);
    Route::get("/likes/user/",[LikesController::class,"ListUserInterest"]);
    Route::get("/likes/interest/{d}/",[LikesController::class,"ListInterestUsers"]);
    Route::post("/likes", [LikesController::class,"Create"]);
    Route::delete("/likes/{d}", [LikesController::class,"Delete"]);

    Route::get("/followers",[FollowsController::class,"ListFollowers"]);
    Route::get("/followeds",[FollowsController::class,"ListFolloweds"]);
    Route::get("/friends",[FollowsController::class,"ListFriends"]);
    Route::post("/follow",[FollowsController::class,"Follow"]);
    Route::post("/unfollow",[FollowsController::class,"UnFollow"]);
    Route::post("/friends",[FollowsController::class,"Friend"]);
});
