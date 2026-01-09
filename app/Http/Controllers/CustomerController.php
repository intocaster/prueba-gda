<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * PUNTO 1: Registro de Clientes
     */
    public function store(Request $request)
    {
        $id_reg = $request->input('id_reg');
        $id_com = $request->input('id_com');

        $comuna = DB::table('communes')
            ->where('id_com', $id_com)
            ->where('id_reg', $id_reg)
            ->where('status', 'A')
            ->first();

        $region = DB::table('regions')
            ->where('id_reg', $id_reg)
            ->where('status', 'A')
            ->first();

        if (!$comuna || !$region) {
            return response()->json(['success' => false, 'message' => 'Región o Comuna inválida'], 400);
        }

        try {
            DB::table('customers')->insert([
                'dni'       => $request->input('dni'),
                'id_reg'    => $id_reg,
                'id_com'    => $id_com,
                'email'     => $request->input('email'),
                'name'      => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'address'   => $request->input('address'),
                'date_reg'  => Carbon::now(),
                'status'    => 'A'
            ]);
            return response()->json(['success' => true, 'message' => 'Registrado'], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * PUNTO 2: Consulta de Clientes (Corregido)
 */
public function show(Request $request)
{
    $search = $request->query('search');

    $query = DB::table('customers as c')
        ->join('regions as r', 'c.id_reg', '=', 'r.id_reg')
        ->join('communes as co', 'c.id_com', '=', 'co.id_com')
        ->select('c.name', 'c.last_name', 'c.dni', 'c.email', 'r.description as region', 'co.description as comuna')
        ->where('c.status', 'A');

    // Si hay búsqueda, aplicamos el filtro
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('c.dni', $search)->orWhere('c.email', $search);
        });
        
        $customer = $query->first(); // Para un solo resultado específico

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['success' => true, 'data' => $customer]);
    }

    // SI NO HAY BÚSQUEDA: Devolvemos la lista completa de activos
    $customers = $query->get();

    return response()->json(['success' => true, 'data' => $customers]);
}

    /**
     * PUNTO 3: Eliminación Lógica
     */
    public function destroy($dni)
    {
        // Buscamos si existe el cliente y si no ha sido "borrado" antes
        $customer = DB::table('customers')
            ->where('dni', $dni)
            ->where('status', 'A')
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no existe o ya fue eliminado'
            ], 404);
        }

        // Realizamos el UPDATE en lugar de un DELETE físico
        DB::table('customers')
            ->where('dni', $dni)
            ->update(['status' => 'trash']);

        return response()->json([
            'success' => true,
            'message' => 'Cliente enviado a la papelera correctamente'
        ]);
    }
}

