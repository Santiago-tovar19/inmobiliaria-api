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

    //si
    Route::post('sign-in', $basePathController.'AuthController@signIn')->name('auth.sign-in');
    //si
    Route::post('check-auth', $basePathController.'AuthController@checkAuth')->name('auth.check-auth')->middleware(['api_access']);
    Route::post('forgot-password', $basePathController.'AuthController@forgotPassword')->name('passwords.sent');
    Route::post('check-password-reset-token', $basePathController.'AuthController@checkPasswordResetToken');
    Route::post('reset-password', $basePathController.'AuthController@resetPassword');
});






//si
Route::get('get-countries', $basePathController.'CountriesController@index');

//si
Route::get('get-all-roles', $basePathController.'EntityPropertiesController@getAllRoles');

Route::get('test', $basePathController.'TestController@index');


// Properties
//si
Route::get('properties/get-features', $basePathController.'PropertiesController@getFeatures');


//si
Route::get('properties/get-feature-properties', $basePathController.'PropertiesController@getFeatureProperties');


//si
Route::post('properties/register-view', $basePathController.'PropertiesController@registerView');
//si
Route::get('properties/get-property-views', $basePathController.'PropertiesController@getPropertyViews');


//si
Route::get('properties/get-property-types', $basePathController.'PropertiesController@getPropertyTypes');
//si
Route::resource('properties', $basePathController.'PropertiesController');

//si
Route::get('appointments/get-all-appointments', $basePathController.'AppointmentController@getAllAppointments');
//si
Route::resource('appointments', $basePathController."AppointmentController");



// Users
Route::post('users/complete-signup/{token}', $basePathController.'UsersController@completeSignUp');
Route::post('users/signup-costumer', $basePathController.'UsersController@signUpCustomer');
Route::post('users/create-user',$basePathController.'UsersController@createUser');


Route::group(['middleware' => ['api_access']], function () use ($basePathController) {


    //si
    Route::get('/dashboard/adminmaster', $basePathController.'DashboardController@getAdmindMasterData');

    // Users
    Route::get('users/resend-signup-email/{id}', $basePathController.'UsersController@resendSignUpEmail');

    //si -- deprecated
    Route::post('users/fav/{propertyId}/{fav}', $basePathController.'UsersController@setFavProperty');

    //si
    Route::get('users/set-property-fav/{propertyId}/{fav}', $basePathController.'UsersController@setFavProperty');
    // registro de usuarios pero solo si estas autenticado
 Route::resource('users', $basePathController.'UsersController');
});

//si
Route::get('brokers/get-all', $basePathController.'BrokersController@getAll');





Route::get('properties-imgs', function () {
    $target = '/home/public_html/storage/app/public';
    $shortcut = '/home/public_html/public/storage';
    symlink($target, $shortcut);
});
