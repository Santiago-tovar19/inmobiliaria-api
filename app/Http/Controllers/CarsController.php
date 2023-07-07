<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$cars = Car::when($request->city, function($q) use ($request) {
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
        ->orderBy('id', 'desc')
        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $cars);
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
        $car = new Car();

        $car->year = $request->year;
        $car->color = $request->color;
        $car->doors = $request->doors;
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->placa = $request->placa;
        $car->owner_name = $request->owner_name;

        $car->save();

        return ApiResponseController::response('Registro Exitoso', 200, $car);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($car = Car::find($id)){
            return ApiResponseController::response('Consulta Exitosa', 200, $car);
        }

        return ApiResponseController::response('No se encontro el registro', 204, null);
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
        if($car = Car::find($id)){
            $car->year = $request->year;
            $car->color = $request->color;
            $car->doors = $request->doors;
            $car->brand = $request->brand;
            $car->model = $request->model;
            $car->placa = $request->placa;
            $car->owner_name = $request->owner_name;

            $car->save();

            return ApiResponseController::response('Actualizacion Exitosa', 200, $car);
        }

        return ApiResponseController::response('No se encontro el registro', 204, null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($car = Car::find($id)){
            $car->delete();

            return ApiResponseController::response('Eliminacion Exitosa', 200, $car);
        }

        return ApiResponseController::response('No se encontro el registro', 204, null);
    }
}
