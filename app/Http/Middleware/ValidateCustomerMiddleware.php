<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class ValidateCustomerMiddleware
{
    public function handle($request, Closure $next)
    {
        // Si no es un POST (registro), deja pasar la petición sin validar nada
    if (!$request->isMethod('post')) {
        return $next($request);
    }
        // A. Validar campos obligatorios
        $requiredFields = ['dni', 'id_reg', 'id_com', 'email', 'name', 'last_name'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'success' => false, 
                    'message' => "Error de validación: El campo $field es requerido"
                ], 400);
            }
        }

        // B. Validar relación Región/Comuna 
        // Buscamos si existe la comuna y si su id_reg coincide con el enviado
        $geoValidation = DB::table('communes')
            ->where('id_com', $request->input('id_com'))
            ->where('id_reg', $request->input('id_reg'))
            ->where('status', 'A')
            ->first();

        if (!$geoValidation) {
            return response()->json([
                'success' => false, 
                'message' => 'La ubicación es inválida: La comuna no pertenece a la región seleccionada.'
            ], 400);
        }

        // C. Validar Unicidad (Evitar duplicados en DNI o Email)
        $exists = DB::table('customers')
            ->where('dni', $request->input('dni'))
            ->orWhere('email', $request->input('email'))
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false, 
                'message' => 'El DNI o Email ya se encuentra registrado en el sistema.'
            ], 400);
        }

        return $next($request);
    }
}