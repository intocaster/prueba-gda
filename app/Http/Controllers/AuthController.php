<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para guardar en la base de datos
use Carbon\Carbon; // Necesario para manejar fechas y horas

class AuthController extends Controller
{
    // función Generar token SHA1 y devolverlo
    public function login(Request $request) {
        // Validar que venga el email (puedes añadir validación de password si usas tabla users)
        $email = $request->input('email');
        
        // 1. Obtener fecha y hora actual
        $dateTime = date('Y-m-d H:i:s');
        
        // 2. Generar random entre 200 y 500 
        $random = rand(200, 500);
        
        // 3. Crear el string para encriptar
        $rawString = $email . $dateTime . $random;
        
        // 4. Encriptar en SHA1 
        $token = sha1($rawString);
        
        // 5. Definir tiempo de vida (ejemplo: 1 hora) 
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
        // Guardar en la tabla personal_access_tokens
        DB::table('personal_access_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
    
        return response()->json([
            'success' => true,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
    }
}