<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        // 1. Extraer el header
        $header = $request->header('Authorization');
        
        if (!$header) {
            return response()->json(['success' => false, 'message' => 'Token no proporcionado'], 401);
        }

        // 2. Limpiar el prefijo Bearer
        $token = str_replace('Bearer ', '', $header);

        // 3. Buscar en la tabla correcta: personal_access_tokens
        $accessToken = DB::table('personal_access_tokens')
            ->where('token', $token)
            ->first();

        // 4. Validar existencia
        if (!$accessToken) {
            return response()->json(['success' => false, 'message' => 'Token inválido o inexistente'], 401);
        }

        // 5. Validar expiración
        if (Carbon::now()->greaterThan(Carbon::parse($accessToken->expires_at))) {
            return response()->json(['success' => false, 'message' => 'El token ha expirado'], 401);
        }

        return $next($request);
    }
}