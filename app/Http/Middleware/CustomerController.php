<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * REGISTRO (Punto 1 y 4)
     */
    public function store(Request $request)
    {
        try {
            DB::table('customers')->insert([
                'dni'       => $request->input('dni'),
                'id_reg'    => $request->input('id_reg'),
                'id_com'    => $request->input('id_com'),
                'email'     => $request->input('email'),
                'name'      => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'address'   => $request->input('address'),
                'date_reg'  => date('Y-m-d H:i:s'),
                'status'    => 'A'
            ]);

            return response()->json(['success' => true, 'message' => 'Cliente registrado'], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al registrar'], 500);
        }
    }

    /**
     * CONSULTA CON JOIN (Punto 2)
     * Este es el método nuevo que soluciona el error "Falta parámetro"
     */
    public function show(Request $request)
    {
        
        // El Requerimiento 2 pide traer descripción de región y comuna
        $query = DB::table('customers as c')
            ->join('regions as r', 'c.id_reg', '=', 'r.id_reg')
            ->join('communes as co', 'c.id_com', '=', 'co.id_com')
            ->select(
                'c.dni', 
                'c.name', 
                'c.last_name', 
                'c.email',
                'r.description as region', 
                'co.description as commune'
            )
            ->where('c.status', 'A'); // Filtro obligatorio: Solo activos

        // Si el usuario envía un parámetro 'search' en la URL, filtramos
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('c.dni', 'like', "%$search%")
                  ->orWhere('c.email', 'like', "%$search%");
            });
        }

        $results = $query->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * ELIMINACIÓN LÓGICA (Punto 3)
     */
    public function destroy($dni)
    {
        $update = DB::table('customers')
            ->where('dni', $dni)
            ->update(['status' => 'trash']);

        if ($update) {
            return response()->json(['success' => true, 'message' => 'Registro eliminado lógicamente']);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el registro'], 404);
    }
}