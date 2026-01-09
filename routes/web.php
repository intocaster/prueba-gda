<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// 0. LOGIN: Ruta pública (Sin middleware para evitar el error de "token no proporcionado")
$router->post('/login', 'AuthController@login'); 

/**
 * GRUPO PROTEGIDO
 * auth.token: Valida el token en personal_access_tokens.
 * audit: Registra logs de la operación.
 */
$router->group(['middleware' => ['auth.token', 'audit']], function () use ($router) { 

    // Registro de Clientes
    $router->post('/customers', [
        'middleware' => 'valida.cliente', 
        'uses' => 'CustomerController@store'
    ]);

    // Consulta de Clientes
    $router->get('/customers/search', 'CustomerController@show');

    // Eliminación Lógica
    $router->delete('/customers/{dni}', 'CustomerController@destroy'); 
    
});

// Ruta base Health Check
$router->get('/', function () use ($router) {
    return response()->json(['framework' => $router->app->version(), 'status' => 'Online']);
});