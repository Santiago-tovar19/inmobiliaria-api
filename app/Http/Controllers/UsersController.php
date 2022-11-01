<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$users = User::with('role')
        ->when($request->city, function($q) use ($request) {
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

        // If file exist
        $imgName = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/files/', $name);
            $imgName = $name;
        }

        // If file exist
        $brokerLogo = null;
        if ($request->hasFile('broker_logo')) {
            $file = $request->file('broker_logo');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/files/', $name);
            $brokerLogo = $name;
        }

        $user                      = new User();
        $user->broker_address      = $request->broker_address;
        $user->broker_logo         = $brokerLogo;
        $user->broker_name         = $request->broker_name;
        $user->email               = $request->email;
        $user->full_name           = $request->full_name;
        $user->img                 = $imgName;
        $user->phone               = $request->phone;
        $user->role_id             = $request->role_id;
        $user->username            = $request->username;

        $user->save();

        return ApiResponseController::response('Usuario creado con exito', 200, $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$user = User::find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $user);
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

        if(!$user = User::find($id)){
            return ApiResponseController::response('', 204);
        }

        $request->img_changed = $request->img_changed == 'true' ? true : false;
        $imgName = null;
        if ($request->img_changed) {
            $file_path = public_path().'/files/'.$user->img;
            \File::delete($file_path);
            if($request->hasFile('img')){
                $file = $request->file('img');
                $name = time().$file->getClientOriginalName();
                $file->move(public_path().'/files/', $name);
                $imgName = $name;
            }
            $user->img = $imgName;
        }

        // If file exist
        $request->broker_logo_changed = $request->broker_logo_changed == 'true' ? true : false;
        $brokerLogo = null;
        if ($request->broker_logo_changed) {
            $file_path = public_path().'/files/'.$user->broker_logo;
            \File::delete($file_path);
            if($request->hasFile('broker_logo')){
                $file = $request->file('broker_logo');
                $name = time().$file->getClientOriginalName();
                $file->move(public_path().'/files/', $name);
                $brokerLogo = $name;
            }
            $user->broker_logo = $brokerLogo;
        }


        $user->broker_address      = $request->broker_address;
        $user->broker_name         = $request->broker_name;
        $user->email               = $request->email;
        $user->full_name           = $request->full_name;
        $user->phone               = $request->phone;
        $user->role_id             = $request->role_id;
        $user->username            = $request->username;

        $user->save();

        return ApiResponseController::response('Usuario actualizado con exito', 200, $user);
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
