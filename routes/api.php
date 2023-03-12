<?php

use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function(){
    Route::post("/register", "AuthController@register");
    Route::get("/validate-token", "AuthController@validateToken")->middleware("auth:api");
});