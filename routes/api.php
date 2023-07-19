<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

$basePathController = 'App\Http\Controllers\\';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () use ($basePathController) {
    Route::post('sign-in', $basePathController.'AuthController@signIn')->name('auth.sign-in');
    Route::post('check-auth', $basePathController.'AuthController@checkAuth')->name('auth.check-auth')->middleware(['api_access']);
    Route::post('forgot-password', $basePathController.'AuthController@forgotPassword')->name('passwords.sent');
    Route::post('check-password-reset-token', $basePathController.'AuthController@checkPasswordResetToken');
    Route::post('reset-password', $basePathController.'AuthController@resetPassword');
});







Route::get('get-countries', $basePathController.'CountriesController@index');
Route::get('get-all-roles', $basePathController.'EntityPropertiesController@getAllRoles');

Route::get('test', $basePathController.'TestController@index');


// Properties
Route::get('properties/get-features', $basePathController.'PropertiesController@getFeatures');
Route::get('properties/get-feature-properties', $basePathController.'PropertiesController@getFeatureProperties');
Route::post('properties/register-view', $basePathController.'PropertiesController@registerView');
Route::resource('properties', $basePathController.'PropertiesController');


// Users
Route::post('users/complete-signup/{token}', $basePathController.'UsersController@completeSignUp');
Route::post('users/signup-costumer', $basePathController.'UsersController@signUpCustomer');

Route::group(['middleware' => ['api_access']], function () use ($basePathController) {

    Route::get('/dashboard/adminmaster', $basePathController.'DashboardController@getAdmindMasterData');

    // Users
    Route::get('users/resend-signup-email/{id}', $basePathController.'UsersController@resendSignUpEmail');
    Route::post('users/fav/{propertyId}/{fav}', $basePathController.'UsersController@setFavProperty');
    Route::get('set-property-fav/{propertyId}/{fav}', $basePathController.'UsersController@setFavProperty');
    Route::resource('users', $basePathController.'UsersController');
});


Route::get('brokers/get-all', $basePathController.'BrokersController@getAll');



Route::get('properties-imgs', function () {
    $target = '/home/public_html/storage/app/public';
    $shortcut = '/home/public_html/public/storage';
    symlink($target, $shortcut);
});
