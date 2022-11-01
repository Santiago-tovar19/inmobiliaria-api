<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntityPropertiesController extends Controller
{
    public function getCourseModalities(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\CourseModality::all());
    }

    public function getCourseStatus(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\CourseStatus::all());
    }

    public function getCourseAcademicClassStatus(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\AcademicClassStatus::all());
    }

    public function getCollageDegreeStatus(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\CollegeDegreeStatus::all());
    }

    public function getTitleStatus(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\TitleStatus::all());
    }

    public function getAllRoles(Request $request)
    {
        return ApiResponseController::response('Exito', 200, \App\Models\Role::all());
    }
}
