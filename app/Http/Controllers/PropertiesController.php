<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$users = Property::with( 'propertyType', 'propertyStatus', 'currency')->when($request->city, function($q) use ($request) {
            $q->where('city', $request->city);
        })
        ->when($request->company, function($q) use ($request) {
            $q->where('compny', $request->company);
        })
        ->when($request->profession, function($q) use ($request) {
            $q->where('profession', $request->profession);
        })
        ->when($request->file, function($q) use ($request) {
            $q->where('file', 'like', '%'.$request->file.'%');
        })
        ->where('id', '!=', $user->id)

        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $users);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$property = Property::find($id)){
            return ApiResponseController::response('', 204);
        }

        // Get related data
        $property->propertyType;
        $property->propertyStatus;
        $property->currency;
        $property->images;

        return ApiResponseController::response('Consulta exitosa', 200, $property);
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
        //
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
