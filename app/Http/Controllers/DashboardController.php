<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $studentsCount = \App\Models\Student::count();
        $coursesCount = \App\Models\Course::count();
        $academicClassesCount = \App\Models\AcademicClass::count();
        $asignaturesCount = \App\Models\Asignature::count();
        $collegeDegreesCount = \App\Models\CollegeDegree::count();

        return ApiResponseController::response('Exito', 200, [
            'studentsCount' => $studentsCount,
            'coursesCount' => $coursesCount,
            'academicClassesCount' => $academicClassesCount,
            'asignaturesCount' => $asignaturesCount,
            'collegeDegreesCount' => $collegeDegreesCount
        ]);
    }


    public function getEgresadosDashboardData(Request $request)
    {
        $studentsCount = \App\Models\Student::count();
        $studentsGraduatesCount = \App\Models\StudentCollegeDegree::where('status_id', 3)->count();
        $studentsEgresadosCount = \App\Models\StudentCollegeDegree::where('status_id', 2)->count();
        $pendingTitles = \App\Models\StudentCollegeDegree::where('title_status_id', 4)->count();
        $retiredTitles = \App\Models\StudentCollegeDegree::where('title_status_id', 5)->count();

        return ApiResponseController::response('Exito', 200, [
            'studentsCount' => $studentsCount,
            'studentsGraduatesCount' => $studentsGraduatesCount,
            'studentsEgresadosCount' => $studentsEgresadosCount,
            'pendingTitles' => $pendingTitles,
            'retiredTitles' => $retiredTitles
        ]);
    }
}
