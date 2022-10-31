<?php

namespace App\Http\Controllers;

use App\Models\Asignature;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AsignaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$asignature = Asignature::with('collegeDegrees')->when(($request->input('searchString')!=''), function($q) use ($request){
			$q->orWhere('name', 'LIKE', '%'.$request->input('searchString').'%')
              ->orWhere('description', 'LIKE', '%'.$request->input('searchString').'%');

		})->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $asignature);
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

        $asignature = new Asignature();
	    $asignature->name = $request->name;
        $asignature->description = $request->description;
        $asignature->save();

	    return ApiResponseController::response('Asignatura creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$asignature = Asignature::with('collegeDegrees')->find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $asignature);
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
        if(!$asignature = Asignature::find($id)){
            return ApiResponseController::response('', 204);
        }
        $asignature->name = $request->name;
        $asignature->description = $request->description;
        $asignature->save();

        $asignature->collegeDegrees()->sync($request->collage_degrees);

        return ApiResponseController::response('Asignatura actualizada exitosamente', 200);
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
        return ApiResponseController::response('Consulta exitosa', 200, Asignature::all());
    }
}
