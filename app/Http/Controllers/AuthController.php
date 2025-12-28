<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\CheckPasswordTokenRequest;

class AuthController extends Controller
{
    public function signIn(SignInRequest $request)
{
    $user = User::where('email', 'santiago@gmail.com')->first();

    if(!$token = auth('api')->tokenById($user->id)){
        $token = JWTAuth::fromUser($user);
    }

    $user->getModules();

    return ApiResponseController::response('Autenticacion simulada', 200, [
        'accessToken' => $token,
        'tokenType' => 'bearer',
        'user' => $user
    ]);
}

public function checkAuth(Request $request)
{
    // Revisa el header Authorization
    $authHeader = $request->header('Authorization');

    // Código normal
    $token = JWTAuth::getToken();
    $info = JWTAuth::decode($token);

    $user = auth()->user();
    $user = User::with('role')->find($user->id);
    $user->getModules();

    return ApiResponseController::response('Refrescado exitosamente', 200, [
        'user' => $user
    ]);
}


		public function forgotPassword(ForgotPasswordRequest $request){
			$user = User::where('email', $request->email)->first();
			if (!$user) {
				return ApiResponseController::response('Usuario no existe', 422);
			}
			// Send email
			$token = Password::broker()->createToken($user);

			$user->notify(new \App\Notifications\MailResetPasswordNotification($token, $user->email));

			return ApiResponseController::response('Correo enviado', 200, $token);
		}

		public function checkPasswordResetToken(CheckPasswordTokenRequest $request){

			$reset = DB::table('password_resets')->where(['email'=> $request->email])->first();

      if(!$reset || !Hash::check($request->token, $reset->token)){
          return ApiResponseController::response('Token invalido', 422);
      }

			// Check if the token has expired
			$expirationDate = Carbon::parse($reset->created_at)->addMinutes(config('auth.passwords.users.expire'));
			if($expirationDate->isPast()){
				return ApiResponseController::response('Token expirado', 422);
			}

			return ApiResponseController::response('Token valido', 200);
		}

		public function resetPassword(ResetPasswordRequest $request)
		{

			$reset = DB::table('password_resets')->where(['email'=> $request->email])->first();

      if(!$reset || !Hash::check($request->token, $reset->token)){
          return ApiResponseController::response('Token invalido', 422);
      }

			$expirationDate = Carbon::parse($reset->created_at)->addMinutes(config('auth.passwords.users.expire'));
			if($expirationDate->isPast()){
				return ApiResponseController::response('Token expirado', 422);
			}


			$data = $request->only('email','token', 'password', 'password_confirmation');

			$response = Password::reset($data, function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->save();
			});

			if($response != Password::PASSWORD_RESET){
				return ApiResponseController::response('Error al reestablecer contraseña', 422);
			}

			return ApiResponseController::response('Contraseña reestablecida, ahora puedes iniciar sesion', 200);
		}

}
