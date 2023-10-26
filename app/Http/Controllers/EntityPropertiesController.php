<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    // public function getAllRoles(Request $request)
    // {
    //     return ApiResponseController::response('Exito', 200, \App\Models\Role::all());
    // }

    public function getAllRoles(Request $request)
{

    // Obtener el ID del usuario autenticado
    $roleId = JWTAuth::parseToken()->authenticate();

    // Verificar si el ID del usuario es igual a 1
    if ($roleId->role_id != 1) {
        // Si el ID del usuario no es igual a 1, obtener todos los roles excluyendo el registro con role_id igual a uno
        $roles = Role::where("role_id", '!=', 1)->get();
    } else {
        // Si el ID del usuario es igual a 1, obtener todos los roles, incluyendo el registro con role_id igual a uno
        $roles = Role::all();
    }

    return ApiResponseController::response('Éxito', 200, $roles);
}
}
