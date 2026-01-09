<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class LogMiddleware
{
    public function handle($request, Closure $next)
    {
        // Antes de la respuesta: Guardar la entrada
        DB::table('logs')->insert([
            'ip_address' => $request->ip(),
            'type'       => 'INPUT',
            'data'       => json_encode($request->all()),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $next($request);

        // Después de la respuesta: Guardar la salida
        DB::table('logs')->insert([
            'ip_address' => $request->ip(),
            'type'       => 'OUTPUT',
            'data'       => $response->getContent(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $response;
    }
}