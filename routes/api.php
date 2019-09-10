<?php

use Illuminate\Http\Request;

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

Route::apiResources([
    'room-setup' => 'API\RoomController',
    'booking' => 'API\BookingController',
    'income-source' => 'API\IncomesourceController',
    'payment' => 'API\PaymentController',
    'expenditure' => 'API\ExpenditureController',
    'credit' => 'API\CreditController',
    'debit' => 'API\DebitController'
]);

Route::post('room-type-setup', 'API\RoomController@RoomTypeStore');
Route::get('room-type', 'API\RoomController@RoomType');
Route::get('room-type-booking/{data}', 'API\RoomController@RoomTypeBooking');

Route::get('income-source-all', 'API\IncomesourceController@IncomeSourceAll');

Route::get('payment-all', 'API\PaymentController@PaymentAll');
Route::post('payment-report', 'API\PaymentController@PaymentReport');

Route::get('expenditure-all', 'API\ExpenditureController@ExpenditureAll');

Route::post('credit-report', 'API\CreditController@SearchCreditReport');
Route::get('credit-month', 'API\CreditController@CreditMonth');
Route::get('total-credit', 'API\CreditController@TotalCredit');

Route::get('debit-month', 'API\DebitController@DebitMonth');
Route::post('debit-report', 'API\DebitController@SearchDebitReport');
Route::get('total-debit', 'API\DebitController@TotalDebit');


Route::get('balance-sheet', 'API\ReportController@index');
Route::post('balance-sheet-search', 'API\ReportController@search');
Route::get('balance-sheet-month', 'API\ReportController@month');
Route::get('total-balance-sheet', 'API\ReportController@TotalBalanceSheet');

Route::get('member/{data}', 'API\BookingController@member');
Route::get('booking-amount', 'API\BookingController@BookingAmount');
Route::get('booking-all-schedule', 'API\BookingController@BookingAllSchedule');
Route::post('booking-schedule-search', 'API\BookingController@BookingScheduleSearch');

