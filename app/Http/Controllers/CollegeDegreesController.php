<?php

namespace App\Http\Controllers;

use App\Models\CollegeDegree;
use Illuminate\Http\Request;

class CollegeDegreesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$collageDegree = CollegeDegree::with('students')->when(($request->input('searchString')!=''), function($q) use ($request){
			$q->orWhere('name', 'LIKE', '%'.$request->input('searchString').'%')
              ->orWhere('description', 'LIKE', '%'.$request->input('searchString').'%');

		})->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $collageDegree);
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
        $collageDegree = new CollegeDegree();
	    $collageDegree->name = $request->name;
        $collageDegree->description = $request->description;
        $collageDegree->save();

        $collageDegree->asignatures()->sync($request->asignatures);

	    return ApiResponseController::response('Maestría creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$collageDegree = CollegeDegree::with('asignatures')->find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $collageDegree);
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
        if(!$collegeDegree = CollegeDegree::find($id)){
            return ApiResponseController::response('', 204);
        }
        $collegeDegree->name = $request->name;
        $collegeDegree->description = $request->description;
        $collegeDegree->save();

        // Sync Asignatures
        $collegeDegree->asignatures()->sync($request->asignatures);

        return ApiResponseController::response('Maestría actualizada exitosamente', 200);
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

    public function getAll(Request $request)
    {
        return ApiResponseController::response('Consulta exitosa', 200, CollegeDegree::with('asignatures')->get());
    }
}
