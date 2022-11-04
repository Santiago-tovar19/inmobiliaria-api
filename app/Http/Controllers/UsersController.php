<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UsersController extends Controller
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

        // If file exist
        $imgName = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/files/', $name);
            $imgName = $name;
        }

        $user             = new User();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->broker_id  = $request->broker_id;
        $user->email      = $request->email;
        $user->img        = $imgName;
        $user->phone      = $request->phone;
        $user->verified   = $request->verified === 'true' ? true : false;
        $user->role_id    = $request->role_id;

        $user->save();

        $rolName = Role::find($user->role_id)->nombre;

		config(['auth.passwords.users.expire' => 10080]);
		$token = Password::broker()->createToken($user);

		$user->notify(new \App\Notifications\MailCreateAccount($token, $user->email, $rolName));

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


        $rolName = Role::find($user->role_id)->nombre;

		$token = Password::broker()->createToken($user);

		config(['auth.passwords.users.expire' => 10080]);

		$user->notify(new \App\Notifications\MailCreateAccount($token, $user->email, $rolName));

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

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->broker_id = $request->broker_id === 'null' ? null : $request->broker_id;
        $user->email     = $request->email;
        $user->phone     = $request->phone;
        $user->verified  = $request->verified === 'true' ? true : false;
        $user->role_id   = $request->role_id;

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


    public function completeSignUp(Request $request, $token)
    {
        $reset = DB::table('password_resets')->where(['email'=> $request->email])->first();

        if(!$reset || !Hash::check($token, $reset->token)){
            return ApiResponseController::response('Token invalido', 422);
        }

		$expirationDate = Carbon::parse($reset->created_at)->addMinutes(config('auth.passwords.users.expire'));
		if($expirationDate->isPast()){
			return ApiResponseController::response('Token expirado', 422);
		}

		$user                    = User::where('email', $request->email)->first();
		$user->full_name         = $request->full_name;
		$user->password          = bcrypt($request->password);
		$user->phone             = $request->phone;
		$user->email_verified_at = Carbon::now();
		$user->save();

		// Borrar el token
		DB::table('password_resets')->where(['email'=> $request->email])->delete();

		return ApiResponseController::response('Registrado exitosamente', 200);
    }

    public function resendSignUpEmail(Request $request, $id)
    {
        if(!$usuario = User::find($id)){
			return ApiResponseController::response('', 204);
		}

		$rolName = Role::find($usuario->role_id)->nombre;

		config(['auth.passwords.users.expire' => 10080]);
		$token = Password::broker()->createToken($usuario);

		$usuario->notify(new \App\Notifications\MailCreateAccount($token, $usuario->email, $rolName));

		return ApiResponseController::response('Correo enviado exitosamente', 200);
    }
}
