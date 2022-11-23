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

		$properties = Property::with( 'propertyType', 'status', 'currency', 'contractType')->when($request->city, function($q) use ($request) {
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
        ->when($user->role_id===3, function($q) use ($request) {
            $q->where('created_by', $request->user()->id);
        })
        ->when($user->role_id===1, function($q) use ($request) {
            return $q->withTrashed();
        })

        ->paginate($perPage);

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Get the authenticated user
        $user = $request->user();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        !$property = Property::when($user->role_id===1, function($q) use ($request) {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        !$property = Property::when($user->role_id===1, function($q) use ($request) {
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
}
