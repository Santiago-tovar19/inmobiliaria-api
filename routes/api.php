<?php

use App\Http\Controllers\StudentsController;
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


// Egresados

Route::get('egresados/get-dashboard-data', $basePathController.'DashboardController@getEgresadosDashboardData');
Route::get('egresados/get-egresados-students', $basePathController.'EgresadosController@getEgresadosStudents');
Route::get('egresados/get-all-egresados', $basePathController.'EgresadosController@getAllEgresados');
Route::get('egresados/export-pdf-egresados', $basePathController.'EgresadosController@exportPDFEgresados');
Route::get('egresados/{id}', $basePathController.'EgresadosController@show');

Route::get('graduates/get-graduates-students', $basePathController.'GraduatesController@getGraduatesStudents');
Route::get('graduates/get-all-graduates', $basePathController.'GraduatesController@getAllGraduates');
Route::get('graduates/export-pdf-graduates', $basePathController.'GrazduatesController@exportPDFGraduates');
Route::get('graduates/{id}', $basePathController.'GraduatesController@show');



Route::get('get-all-asignatures', $basePathController.'AsignaturesController@getAll');
Route::get('get-all-college-degrees', $basePathController.'CollegeDegreesController@getAll');
Route::post('students/upload-files', $basePathController.'StudentsController@uploadStudentFile');
Route::post('students/upload-score-file', $basePathController.'TestController@uploadScoreFile');
Route::get('students/get-with-graduate-and-egresado-college-degree/{id}', $basePathController.'StudentsSharedController@showWithGraduateAndEgresadoCollegeDegree');

Route::delete('students/remove-college-degree/{studentID}/{collegeDegreeID}', $basePathController.'StudentsSharedController@removeCollegeDegree');
Route::post('students/upload-graduate-file', $basePathController.'StudentsController@uploadStudentsGraduatesFile');
Route::post('update-student/{id}', $basePathController.'StudentsController@update');
Route::get('students/get-missing-asignatures/{collegeDegreeID}/{studentID}', $basePathController.'StudentsSharedController@getMissingAsignatures');

Route::get('students/export-scores-pdf/{studentID}', $basePathController.'StudentsSharedController@exportScoresPDF');
Route::resource('students', $basePathController.'StudentsController');
Route::resource('college-degrees', $basePathController.'CollegeDegreesController');
Route::resource('asignatures', $basePathController.'AsignaturesController');
Route::resource('courses', $basePathController.'CoursesController');

Route::resource('academic-classes', $basePathController.'AcademicClassesController');

Route::get('get-course-modalities', $basePathController.'EntityPropertiesController@getCourseModalities');
Route::get('get-course-status', $basePathController.'EntityPropertiesController@getCourseStatus');
Route::get('get-academic-class-status', $basePathController.'EntityPropertiesController@getCourseAcademicClassStatus');
Route::get('get-collage-degree-status', $basePathController.'EntityPropertiesController@getCollageDegreeStatus');
Route::get('get-title-status', $basePathController.'EntityPropertiesController@getTitleStatus');

Route::get('get-dashboard-data', $basePathController.'DashboardController@getDashboardData');



Route::get('get-countries', $basePathController.'CountriesController@index');
Route::get('get-all-roles', $basePathController.'EntityPropertiesController@getAllRoles');

Route::get('test', $basePathController.'TestController@index');




// Directory

// Route::post('contacts/upload-file', $basePathController.'ContactsController@uploadDocument');
// Route::get('contacts/get-property-entities', $basePathController.'ContactsController@getPropertyEntities');
// Route::get('contacts/get-all', $basePathController.'ContactsController@getAll');
Route::resource('users', $basePathController.'UsersController');


