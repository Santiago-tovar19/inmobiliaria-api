<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$course = Course::with('collegeDegree', 'asignature')->when(($request->input('searchString')!=''), function($q) use ($request){
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
        $course = new Course();
        $course->hours = $request->hours;
        $course->class_number = $request->class_number;
        $course->modality = $request->modality;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->college_degree_id = $request->college_degree_id;
        $course->asignature_id = $request->asignature_id;
        $course->status = $request->status;
        $course->save();

        // Sync students
        $course->students()->sync($request->students);

	    return ApiResponseController::response('Curso creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$course = Course::with('students', 'asignature')->find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $course);
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
        if(!$course = Course::find($id)){
            return ApiResponseController::response('', 204);
        }

        $course->hours = $request->hours;
        $course->class_number = $request->class_number;
        $course->modality = $request->modality;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->college_degree_id = $request->college_degree_id;
        $course->asignature_id = $request->asignature_id;
        $course->status = $request->status;
        $course->save();

        // Sync students
        $course->students()->sync($request->students);


        return ApiResponseController::response('Curso actualizada exitosamente', 200);
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
