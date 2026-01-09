<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthCustomMiddleware
{
    // Función encargada de interceptar la petición y validar el acceso
    public function handle($request, Closure $next)
    {
        // Extraigo el token de la cabecera 'Authorization'
        // El formato esperado es: Bearer 365aba89...
        $header = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $header);

        if (!$token) {
            return response()->json([
                'success' => false, 
                'message' => 'Token de seguridad no proporcionado'
            ], 401);
        }

        // Busco el token en mi base de datos para verificar su existencia
        $tokenData = DB::table('access_tokens')
            ->where('token', $token)
            ->first();

        // Si no existe el registro, el acceso es denegado
        if (!$tokenData) {
            return response()->json([
                'success' => false, 
                'message' => 'Token inválido o inexistente'
            ], 401);
        }

        // Valido si la fecha actual es mayor a la fecha de expiración guardada
        if (Carbon::now()->greaterThan(Carbon::parse($tokenData->expires_at))) {
            return response()->json([
                'success' => false, 
                'message' => 'El token ha expirado. Inicie sesión nuevamente'
            ], 401);
        }

        // Si todo está en orden, permito que la petición siga su curso
        return $next($request);
    }
}