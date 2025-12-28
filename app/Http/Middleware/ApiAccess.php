<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiResponseController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    \Log::info('ApiAccess middleware ejecutado');

    try {
        $user = JWTAuth::parseToken()->authenticate();
        \Log::info('Token válido. Usuario autenticado: ' . $user->email);
    } catch (\Exception $e) {
        \Log::error('Error de token: ' . $e->getMessage());

        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
            return ApiResponseController::response('Token invalido', 401);
        }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
            return ApiResponseController::response('Token expirado', 401);
        }else{
            return ApiResponseController::response('Token no encontrado', 401);
        }
    }

    return $next($request);
}

}
