<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 1. Obtener el token del header Authorization: Bearer {token}
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token no proporcionado'], 401);
        }

        // 2. Buscar el token en la tabla correcta
        $session = \DB::table('personal_access_tokens')
            ->where('token', $token)
            ->first();

        // 3. Validar si existe
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
        }

        // 4. Validar si expiró
        if (new \DateTime() > new \DateTime($session->expires_at)) {
            return response()->json(['success' => false, 'message' => 'El token ha expirado'], 401);
        }

        return $next($request);
    }
}
