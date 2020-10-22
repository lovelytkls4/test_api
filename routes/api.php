<?php
// routes/api.php
 
use Illuminate\Http\Request;
 
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login')->name('login');
    Route::post('signup', 'App\Http\Controllers\AuthController@signup')->name('signup');
   
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::delete('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
        Route::get('me', 'App\Http\Controllers\AuthController@user')->name('user');
    });
});