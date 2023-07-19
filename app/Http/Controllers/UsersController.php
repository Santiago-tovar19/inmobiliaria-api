<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpCustomerRequest;
use App\Models\Property;
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
        ->when($request->termino, function($q) use ($request) {
            $q->orWhere('first_name', 'like', '%'.$request->termino.'%')
            ->orWhere('email', 'like', '%'.$request->termino.'%');
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


    /**
     * @OA\Post(
     *     path="/property/{propertyId}/{fav}",
     *     summary="Añadir o eliminar una propiedad de favoritos",
     *     tags={"Users"},
     *     @OA\Parameter( name="propertyId", in="path", description="ID de la propiedad", required=true, @OA\Schema( type="integer", format="int64")
     *     ),
     *     @OA\Parameter(name="fav",in="path",description="Indica si se debe añadir o eliminar la propiedad de favoritos, donde 1 es para añadir a favoritos y 0 es para remover de favoritos",required=true,@OA\Schema(type="int")),
     *     @OA\Response(
     *         response=200,
     *         description="Propiedad añadida o eliminada de favoritos con éxito",
     *           @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *           )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Propiedad no encontrada",
     *          @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *           )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Propiedad ya está en favoritos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *           )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function setFavProperty(Request $request, $propertyId, $fav){

        if(!$property = Property::find($propertyId)){
            return ApiResponseController::response('Propiedad no encontrada', 404);
        }

        $user = $request->user();

        if($fav){
            // Check if property is already in favs
            if($user->favProperties()->where('property_id', $propertyId)->first()){
                return ApiResponseController::response('Propiedad ya esta en favoritos', 422);
            }
            $user->favProperties()->attach($propertyId);
        }

        if(!$fav){
            $user->favProperties()->detach($propertyId);
        }

        return ApiResponseController::response('Propiedad añadida a favoritos', 200);

    }


    /**
     * @OA\Post(
     *     path="/api/users/sign-up",
     *     summary="Sign up a customer",
     *     description="Registers a new customer user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SignUpCustomerRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Registrado exitosamente"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="accessToken",
     *                     type="string",
     *                     example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *                 ),
     *                 @OA\Property(
     *                     property="tokenType",
     *                     type="string",
     *                     example="bearer"
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Credenciales invalidas"
     *             )
     *         )
     *     )
     * )
     */
    public function signUpCustomer(SignUpCustomerRequest $request){

        User::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'password' => bcrypt($request->password),
            'role_id' => Role::where('name', 'Consumidor')->first()->id,
        ]);

        // Get user
        $user = User::where('email', $request->email)->first();

        if (! $token = auth('api')->tokenById($user->id)) {
            return ApiResponseController::response('Credenciales invalidas', 401);
        }

        $data = [
			'accessToken' => $token,
			'tokenType' => 'bearer',
			'user' => $user
		];

        return ApiResponseController::response('Registrado exitosamente', 200, $data);
    }
}
