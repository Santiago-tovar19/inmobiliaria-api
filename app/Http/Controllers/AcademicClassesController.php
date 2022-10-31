<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use Illuminate\Http\Request;

class AcademicClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Get course from query string
        $courseID = $request->input('courseID');
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$course = AcademicClass::where('course_id', $courseID)->when(($request->input('searchString')!=''), function($q) use ($request){
			// Get all columns
            $columns = \Schema::getColumnListing('courses');
            foreach($columns as $column){
                $q->orWhere($column, 'LIKE', '%'.$request->input('searchString').'%');
            }
		})->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $course);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get Course ID from query params
        $academicClass = new AcademicClass();
        $academicClass->datetime = $request->datetime;
        $academicClass->class_number = $request->class_number;
        $academicClass->status = $request->status;
        $academicClass->course_id = $request->course_id;
        $academicClass->save();

        // Sync attendence
        $academicClass->attendance()->sync($request->attendance);


	    return ApiResponseController::response('Clase creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$academicClass = AcademicClass::with('course.students', 'attendance')->find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $academicClass);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$academicClass = AcademicClass::find($id)){
            return ApiResponseController::response('', 204);
        }
        $academicClass->datetime = $request->datetime;
        $academicClass->class_number = $request->class_number;
        $academicClass->status = $request->status;
        $academicClass->course_id = $request->course_id;
        $academicClass->save();

        // Delete all attendances
        $academicClass->attendance()->detach();

        // Sync students
        $academicClass->attendance()->sync($request->attendance);


        return ApiResponseController::response('Clase actualizada exitosamente', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
