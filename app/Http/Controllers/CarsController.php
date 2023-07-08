<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{


    /**
     * @OA\Get(
     *     path="/cars",
     *     summary="Obtener lista de carros",
     *     tags={"Carros"},
     *     @OA\Parameter( name="per_page", in="query", description="Numero de registros", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter( name="page", in="query", description="Numero de la pagina a consultar", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter( name="brand", in="query", description="Marca del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="model", in="query", description="Modelo del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="placa", in="query", description="Placa del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="owner_name", in="query", description="Nombre del propietario del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="year", in="query", description="Año del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="color", in="query", description="Color del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter( name="doors", in="query", description="Numero de puertas del carro", required=false, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response="200",
     *         description="Operación exitosa",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *                     @OA\Property(
     *                         property="data",
     *                         type="object",
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             @OA\Property(type="array", property="data", @OA\Items(ref="#/components/schemas/Car"))
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {

        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$cars = Car::
        when($request->year, function($q) use ($request) {
            $q->where('year', 'like', '%'.$request->year.'%');
        })
        ->when($request->color, function($q) use ($request) {
            $q->where('color', 'like', '%'.$request->color.'%');
        })
        ->when($request->doors, function($q) use ($request) {
            $q->where('doors', 'like', '%'.$request->doors.'%');
        })
        ->when($request->brand, function($q) use ($request) {
            $q->where('brand', 'like', '%'.$request->brand.'%');
        })
        ->when($request->model, function($q) use ($request) {
            $q->where('model', 'like', '%'.$request->model.'%');
        })
        ->when($request->placa, function($q) use ($request) {
            $q->where('placa', 'like', '%'.$request->placa.'%');
        })
        ->when($request->owner_name, function($q) use ($request) {
            $q->where('owner_name', 'like', '%'.$request->owner_name.'%');
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
     * @OA\Post(
     *     path="/cars",
     *     summary="Crear un nuevo carro",
     *     tags={"Carros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Car")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Exitoso",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/GeneralResponse"),
     *                      @OA\Property(
     *                          property="data",
     *                          type="object",
     *                          @OA\Property(
     *                              property="data",
     *                              type="object",
     *                              ref="#/components/schemas/Car"
     *                          )
     *                      )
     *                  }
     *              )
     *           )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validacion de campos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                  property="errors",
     *                  @OA\Property(
     *                     property="brand",
     *                    type="array",
     *                   @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     ),
     * )
     */

    public function store(CarRequest $request)
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
     * @OA\Get(
     *     path="/cars/{id}",
     *     summary="Obtener información de un carro",
     *     tags={"Carros"},
     *     @OA\Parameter( name="id", in="path", description="ID del carro", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Consulta Exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se encontró el registro",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
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
     * @OA\Put(
     *     path="/cars/{id}",
     *     summary="Actualizar información de un carro",
     *     tags={"Carros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del carro",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Car")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualización Exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se encontró el registro",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(CarRequest $request, $id)
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
     * @OA\Delete(
     *     path="/cars/{id}",
     *     summary="Eliminar un carro",
     *     tags={"Carros"},
     *     @OA\Parameter( name="id", in="path", description="ID del carro", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminación Exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se encontró el registro",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
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
