<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderResourceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SiteScoreAhrefsController;
use App\Http\Controllers\DashboardController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
