<?php

use App\Http\Controllers\{GameController, UsersController};
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function(){
    Route::post("/register", "AuthController@register");
    Route::post("/login", "AuthController@login");
    Route::post("/login-admin", "AuthController@login_admin");
    Route::get("/validate-token", "AuthController@validateToken")->middleware("auth:api");
    Route::get("/active-user/{id}", "AuthController@activeUser");
});

Route::controller(UsersController::class)->middleware("auth:api")->prefix("/admin")->group(function(){
    Route::get("/get-inactive-users", "getInactiveUsers");
    Route::get("/get-scores/{level}", "getScoresByLevel");
    Route::get("/get-users-finished-level/{level}", "getUsersFinishedLevel");
});

Route::controller(GameController::class)->middleware("auth:api")->prefix("game")->group(function(){
    Route::post("/save-score", "saveScore");
    Route::post("/save-finished-level", "saveFinishedLevel");
});