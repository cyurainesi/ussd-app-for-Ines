<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/ussd-requests", "UssdController@ussdRequest");
Route::resource("destinations", "DestinationController");
Route::resource("bookings", "BookingController");
Route::post("/payment-response", "BookingController@paymentResponse");
Route::post("/opay/payment-response", "BookingController@opayPaymentResponse");
Route::post("/validate-booking/{transactionId}", "BookingController@validateBooking");
Route::get("/booking-reports","BookingController@reports");
