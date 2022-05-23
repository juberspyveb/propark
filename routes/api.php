<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
#use App\Http\Controllers\ProductController as DefaultProductController;
use App\Http\Controllers\Api\AuthController;
#use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LotController;
use App\Http\Controllers\Api\NumberPlatController;

#https://www.avyatech.com/rest-api-with-laravel-8-using-jwt-token/
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('get-user', [AuthController::class, 'getUser']);
#Route::post('register', [ApiController::class, 'register']);
//Route::get('/category', [CategoryController::class,'index']);
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('update-online-status', [UserController::class, 'updateOnlineStatus']);
    Route::get('get-bays', [LotController::class, 'getBays']);
    Route::post('check-number-plate', [NumberPlatController::class, 'checkNumberPlate']);
    Route::post('create-customer', [NumberPlatController::class, 'createCustomer']);
    Route::get('customer/{id}', [NumberPlatController::class, 'getCustomer']);
    Route::post('add-time', [LotController::class, 'addTime']);
    Route::post('release-bay', [LotController::class, 'releaseBay']);
    Route::get('get-alerts', [LotController::class, 'getAlerts']);
    Route::get('get-transactions', [LotController::class, 'getTransactions']);
    Route::post('add-log-issue', [LotController::class, 'addLogIssue']);
    Route::get('attendant-overview', [LotController::class, 'attendantOverview']);
    #Route::resource('lot', 'Api\LotController');
    #Route::resource('guest', 'Api\CategoryController');

    /**
        #Tax
        Route::get('restaurant/tax', 'Api\Restaurant\TaxController@index');
        Route::get('restaurant/tax/{taxId}', 'Api\Restaurant\TaxController@show');
        Route::post('restaurant/tax', 'Api\Restaurant\TaxController@addTax');
        Route::put('restaurant/tax/{taxId}', 'Api\Restaurant\TaxController@updateTax');
        Route::delete('restaurant/tax/{taxId}', 'Api\Restaurant\TaxController@destroy');
        Route::get('restaurant/tax/{taxId}/products', 'Api\Restaurant\TaxController@taxProductList');
      **/
#    Route::resource('category', 'Api\CategoryController');
    /*
     Verb          Path                        Action  Route Name
    GET           /users                      index   users.index
    POST          /users                      store   users.store
    GET           /users/{user}               show    users.show
    PUT|PATCH     /users/{user}               update  users.update
    DELETE        /users/{user}               destroy users.destroy
      */



});