<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            // Obtenemos el token del Header Authorization: Bearer {token}
            $token = $request->bearerToken();

            if ($token) {
                // CAMBIO AQUÍ: Usamos personal_access_tokens y buscamos el usuario asociado
                $session = \DB::table('personal_access_tokens')
                    ->where('token', $token)
                    ->where('expires_at', '>', new \DateTime()) // Validamos expiración de una vez
                    ->first();

                if ($session) {
                    // Retornamos el usuario (esto es lo que Lumen espera para marcar como "Autenticado")
                    return User::where('email', $session->email)->first();
                }
            }

            return null;
        });
    }
}
