<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use App\Models\Currency;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\PropertyView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class PropertiesController extends Controller
{
  /**
 * @OA\Get(
 *     path="/properties",
 *     summary="Obtener todas las propiedades",
 *     description="Obtiene todas las propiedades con paginación y filtros opcionales",
 *     tags={"Properties"},
 *     @OA\Parameter(name="perPage", in="query", description="Número de elementos por página", required=false, @OA\Schema(type="integer", default=10)),
 *     @OA\Parameter(name="name", in="query", description="Nombre de la propiedad", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="description", in="query", description="Descripción de la propiedad", required=false, @OA\Schema(type="string" )),
 *     @OA\Parameter(name="property_type_id", in="query", description="Tipo de propiedad", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="address", in="query", description="Dirección de la propiedad", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="mls_number", in="query", description="Número MLS", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="construction_year", in="query", description="Año de construcción", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="location_type", in="query", description="Tipo de localización", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="bedrooms", in="query", description="Dormitorios", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="bathrooms", in="query", description="Baños", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="size", in="query", description="Tamaño", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="price", in="query", description="Precio", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="currency_id", in="query", description="Tipo de moneda", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="status_id", in="query", description="Estado de la propiedad", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="contract_type_id", in="query", description="Tipo de contrato", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="parking", in="query", description="Estacionamiento", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="kitchen", in="query", description="Cocina", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="elevator", in="query", description="Elevador", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="wifi", in="query", description="Wifi", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="fireplace", in="query", description="Chimenea", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="hoa", in="query", description="HOA", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="stories", in="query", description="Cuentos", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="exclusions", in="query", description="Exclusiones", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="level", in="query", description="Piso (Apartamento)", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="security", in="query", description="Seguridad", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="lobby", in="query", description="Vestíbulo", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="balcony", in="query", description="Balcon", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="terrace", in="query", description="Terraza", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="power_plant", in="query", description="Planta electrica", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="gym", in="query", description="Gimnasio", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="walk_in_closet", in="query", description="Vestidor", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="swimming_pool", in="query", description="Piscina", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="kids_area", in="query", description="Área de niños", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="pets_allowed", in="query", description="Mascotas permitidas", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="central_air_conditioner", in="query", description="Aire acondicionado central", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="published", in="query", description="Publicado", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="fav", in="query", description="Favorito", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="featured", in="query", description="Destacado", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="orderBy", in="query", description="Ordenar por columna", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="order", in="query", description="Ordenar ascendentemente o descendentemente", required=false, @OA\Schema(type="string")),
 *
 *
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
     *                             @OA\Property(type="array", property="data", @OA\Items(ref="#/components/schemas/Property"))
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="No autorizado"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Error interno del servidor"
 *             )
 *         )
 *     )
 * )
 */
public function index(Request $request)
{
    $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

    $query = Property::with('propertyType', 'status', 'images', 'currency', 'contractType');

    // Filtrar por columnas comúnmente utilizadas para buscar propiedades
    $searchColumns = ["name", "description", "property_type_id", "address", "mls_number", "construction_year", "location_type", "bedrooms", "bathrooms", "size", "price", "currency_id", "status_id", "contract_type_id", "parking", "kitchen", "elevator", "wifi", "fireplace", "hoa", "stories", "exclusions", "level", "security", "lobby", "balcony", "terrace", "power_plant", "gym", "walk_in_closet", "swimming_pool", "kids_area", "pets_allowed", "central_air_conditioner", "featured", "published"];

    foreach ($searchColumns as $column) {
        if ($request->filled($column)) {
            $query->where($column, 'LIKE', '%' . $request->input($column) . '%');
        }

        // order by
        if ($request->filled('orderBy') && $request->filled('order')) {
            $query->orderBy($request->input('orderBy'), $request->input('order'));
        }
    }

    try {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->role_id === 2) {
            // Administrador (rol 2): Filtre por broker_id
            $query->where('broker_id', $user->broker_id);
        }
        if ($user->role_id === 3) {
            // Agente (rol 3): Filtre por created_by
            $query->where('created_by', $user->id);
        }
    } catch (\Exception $e) {
        // Manejar la excepción si no se puede autenticar al usuario
    }

    if ($request->input("fav")) {
        $query->whereHas('favUsers', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    $properties = $query->paginate($perPage);

    return ApiResponseController::response('Consulta Exitosa', 200, $properties);
}

    public function getFeatureProperties(Request $request)
    {
        $user = $request->user();

        $propertyID = $request->input('property_id');

        // Randomize the results
        $properties = Property::with( 'propertyType', 'status', 'images', 'currency', 'contractType')->where('id', '<>', $propertyID) ->inRandomOrder()->limit(5)->get();

        return ApiResponseController::response('Consulta Exitosa', 200, $properties);
    }



    public function registerView(Request $request)
    {
        $userID = $request->input('userID');
        $propertyID = $request->input('propertyID');

        $propertyView = PropertyView::create([
            'user_id' => $userID,
            'property_id' => $propertyID
        ]);

        return ApiResponseController::response('Consulta Exitosa', 200, $propertyView);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * @OA\Post(
     *     path="/properties",
     *     summary="Crear una propiedad",
     *     tags={"Properties"},
     *     @OA\RequestBody( required=true, @OA\JsonContent(ref="#/components/schemas/Property")*     ),
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
     *                              ref="#/components/schemas/Property"
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
    public function store(Request $request)
    {

        // Get the authenticated user
        $user = null;
        try{
            $user = JWTAuth::parseToken()->authenticate();

        } catch (\Exception $e) {}


        $images = $request->images;
        $imgs = [];
        if($images) {
            foreach($images as $image) {
                // store in public folder
                $name = $image->store('public/properties');
                $name = explode('/', $name);
                $name = $name[count($name)-1];
                $imgs[] = [
                    'name' => $name,
                    'type' => 'Gallery'
                ];
            }
        }

        $bannerImgs = $request->bannerImgs;
        if($bannerImgs) {
            foreach($bannerImgs as $bannerImg) {
                // store in public folder
                $name = $bannerImg->store('public/properties');
                $name = explode('/', $name);
                $name = $name[count($name)-1];
                $imgs[] = [
                    'name' => $name,
                    'type' => 'Banner'
                ];
            }
        }

        // Check if video is present
        $video = $request->video ?? null;
        if($video) {
            // store in public folder
            $name = $video->store('public/properties');
            $name = explode('/', $name);
            $name = $name[count($name)-1];
            $video = $name;
        }

        $data = $request->all();

        // replace null string to null
        foreach($data as $key => $value) {
            if($value == 'null') {
                $data[$key] = null;
            }
            if($value == 'undefined') {
                $data[$key] = null;
            }
            if($value == 'false') {
                $data[$key] = false;
            }
            if($value == 'true') {
                $data[$key] = true;
            }
        }

        $property                    = new Property();
        $property->name              = $data['name'];
        $property->description       = $data['description'];
        $property->address           = $data['address'];
        $property->construction_year = $data['construction_year'];
        $property->bedrooms          = $data['bedrooms'];
        $property->bathrooms         = $data['bathrooms'];
        $property->size              = $data['size'];
        $property->price             = $data['price'];
        $property->youtube_link      = $data['youtube_link'];
        $property->property_type_id  = $data['property_type_id'];
        $property->currency_id       = $data['currency_id'];
        $property->status_id         = $data['status_id'];
        $property->contract_type_id  = $data['contract_type_id'];
        $property->parking           = $data['parking'];
        $property->kitchen           = $data['kitchen'];
        $property->elevator          = $data['elevator'];
        $property->wifi              = $data['wifi'];
        $property->fireplace         = $data['fireplace'];
        $property->security          = $data['security'];
        $property->lobby             = $data['lobby'];
        $property->lat               = $data['lat'];
        $property->lon               = $data['lon'];
        $property->balcony           = $data['balcony'];
        $property->published         = $data['published'];
        $property->terrace           = $data['terrace'];
        $property->power_plant       = $data['power_plant'];
        $property->gym               = $data['gym'];
        $property->video             = $video;
        $property->walk_in_closet    = $data['walk_in_closet'];
        $property->swimming_pool     = $data['swimming_pool'];
        $property->kids_area         = $data['kids_area'];
        $property->pets_allowed      = $data['pets_allowed'];
        $property->created_by        = $user->id;

        if($property->published) {
            $property->published_at = Carbon::now();
        }

        $property->save();

        $property->images()->createMany($imgs);

        $property->propertyType;
        $property->status;
        $property->currency;
        $property->images;

        return ApiResponseController::response('Property creado con exito', 200, $property);
    }

    /**
 * @OA\Get(
 *     path="/properties/{id}",
 *     summary="Obtener detalles de una propiedad",
 *     tags={"Properties"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la propiedad",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Consulta exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Property")
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No se encontró la propiedad"
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */
    public function show(Request $request, $id)
    {
        $isAdmin = false;
        try{
            $isAdmin = JWTAuth::parseToken()->authenticate()->role_id === 1;

        } catch (\Exception $e) {}

        !$property = Property::when($isAdmin, function($q) use ($request) {
            return $q->withTrashed();
        })->find($id);

        if(!$property){
            return ApiResponseController::response('', 204);
        }

        // Get related data
        $property->propertyType;
        $property->status;
        $property->currency;
        $property->images;
        $property->createdBy;
        $property->contractType;

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
 * @OA\Put(
 *     path="/properties/{id}",
 *     summary="Actualizar una propiedad existente",
 *     tags={"Properties"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la propiedad",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Property")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Propiedad actualizada con éxito",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Property")
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No se encontró la propiedad",
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */
    public function update(Request $request, $id)
    {
        $isAdmin = false;
        try{
            $isAdmin = JWTAuth::parseToken()->authenticate()->role_id === 1;

        } catch (\Exception $e) {}

        !$property = Property::when($isAdmin, function($q) use ($request) {
            return $q->withTrashed();
        })->find($id);

        if(!$property){
            return ApiResponseController::response('', 204);
        }

        $data = $request->all();

        // replace null string to null
        foreach($data as $key => $value) {
            if($value == 'null') {
                $data[$key] = null;
            }
            if($value == 'undefined') {
                $data[$key] = null;
            }
            if($value == 'false') {
                $data[$key] = false;
            }
            if($value == 'true') {
                $data[$key] = true;
            }
        }

        $property->name              = $data['name'];
        $property->description       = $data['description'];
        $property->address           = $data['address'];
        $property->construction_year = $data['construction_year'];
        $property->bedrooms          = $data['bedrooms'];
        $property->bathrooms         = $data['bathrooms'];
        $property->size              = $data['size'];
        $property->price             = $data['price'];
        $property->youtube_link      = $data['youtube_link'];
        $property->property_type_id  = $data['property_type_id'];
        $property->currency_id       = $data['currency_id'];
        $property->status_id         = $data['status_id'];
        $property->contract_type_id  = $data['contract_type_id'];
        $property->parking           = $data['parking'];
        $property->kitchen           = $data['kitchen'];
        $property->elevator          = $data['elevator'];
        $property->wifi              = $data['wifi'];
        $property->fireplace         = $data['fireplace'];
        $property->security          = $data['security'];
        $property->published         = $data['published'];
        $property->lat               = $data['lat'];
        $property->lon               = $data['lon'];
        $property->lobby             = $data['lobby'];
        $property->balcony           = $data['balcony'];
        $property->terrace           = $data['terrace'];
        $property->power_plant       = $data['power_plant'];
        $property->gym               = $data['gym'];
        $property->walk_in_closet    = $data['walk_in_closet'];
        $property->swimming_pool     = $data['swimming_pool'];
        $property->kids_area         = $data['kids_area'];
        $property->pets_allowed      = $data['pets_allowed'];

        if($property->published && !$property->published_at) {
            $property->published_at = Carbon::now();
        }

        // return $request->trashed;
        if(!$data['trashed']) {
            $property->deleted_at = null;
        }

        if($request->deleteVideo=='true') {
            // Delete video from storage
            Storage::delete($property->video);
            $property->video = null;
        }

        $video = $request->video;
        if($video) {
            // store in public folder
            $name = $video->store('public/properties');
            $name = explode('/', $name);
            $name = $name[count($name)-1];
            $property->video = $name;
        }


        $property->save();


        $filesToRemove = explode(",", $request->filesToRemove);
        // return $filesToRemove;
        // Remove images
        if($filesToRemove) {
            foreach($filesToRemove as $fileID) {
                // Delete image from storage
                if($image = PropertyImage::find($fileID)){
                    Storage::delete($image->name);
                    // Delete image from database
                    $image->delete();
                }
            }
        }

        $images = $request->images;
        $imgs = [];
        if($images) {
            foreach($images as $image) {
                // store in public folder
                $name = $image->store('public/properties');
                $name = explode('/', $name);
                $name = $name[count($name)-1];
                $imgs[] = [
                    'name' => $name,
                    'type' => 'Gallery'
                ];
            }
        }

        $bannerImgs = $request->bannerImgs;
        if($bannerImgs) {
            foreach($bannerImgs as $bannerImg) {
                // store in public folder
                $name = $bannerImg->store('public/properties');
                $name = explode('/', $name);
                $name = $name[count($name)-1];
                $imgs[] = [
                    'name' => $name,
                    'type' => 'Banner'
                ];
            }
        }

        if($imgs) {
            $property->images()->createMany($imgs);
        }

        $property->propertyType;
        $property->status;
        $property->currency;
        $property->images;

        return ApiResponseController::response('Actualizado correctamente', 200, $property);
    }

    /**
 * @OA\Delete(
 *     path="/properties/{id}",
 *     summary="Eliminar una propiedad",
 *     tags={"Properties"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la propiedad a eliminar",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Propiedad eliminada correctamente",
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Propiedad no encontrada",
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */
    public function destroy($id)
    {
        if(!$property = Property::find($id)){
            return ApiResponseController::response('', 204);
        }
        // Delete property from database
        $property->delete();

        return ApiResponseController::response('Eliminado correctamente', 200);
    }


    /**
 * @OA\Get(
 *     path="/properties/get-features",
 *     summary="Obtener características",
 *     tags={"Properties"},
 *     @OA\Response(
 *         response=200,
 *         description="Consulta exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="currencies",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Currency")
 *             ),
 *             @OA\Property(
 *                 property="propertyTypes",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PropertyType")
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PropertyStatus")
 *             ),
 *             @OA\Property(
 *                 property="contractTypes",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/ContractType")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */
    public function getFeatures(Request $request)
    {
        $currencies = Currency::all();
        $propertyTypes = PropertyType::all();
        $status = PropertyStatus::all();
        $contractTypes = ContractType::all();

        return ApiResponseController::response('Consulta exitosa', 200, [
            'currencies' => $currencies,
            'propertyTypes' => $propertyTypes,
            'status' => $status,
            'contractTypes' => $contractTypes
        ]);
    }



    public function getPropertyTypes(Request $request)
    {
        $propertyTypes = PropertyType::withCount('properties')->get();

        return ApiResponseController::response('Consulta exitosa', 200, $propertyTypes);
    }
}
