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
            return response()->json(['success' => false, 'message' => 'Región o Comuna inválida o inactiva'], 400);
        }

        try {
            DB::table('customers')->insert([
                'dni'       => $request->input('dni'),
                'id_reg'    => $id_reg,
                'id_com'    => $id_com,
                'email'     => $request->input('email'),
                'name'      => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'address'   => $request->input('address'), // Ya lo tienes, ¡bien!
                'date_reg'  => Carbon::now(),
                'status'    => 'A'
            ]);
            return response()->json(['success' => true, 'message' => 'Cliente registrado con éxito'], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al registrar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUNTO 2: Búsqueda de Clientes (Solo activos y que coincidan con DNI o Apellido)
     */
    public function search(Request $request)
    {
        $search = $request->input('search'); // Puede ser DNI o Apellido

        $customers = DB::table('customers')
            ->join('regions', 'customers.id_reg', '=', 'regions.id_reg')
            ->join('communes', 'customers.id_com', '=', 'communes.id_com')
            ->select(
                'customers.name', 
                'customers.last_name', 
                'customers.address', // Agregado para el reporte
                'regions.description as region', 
                'communes.description as commune'
            )
            ->where('customers.status', 'A')
            ->where(function($query) use ($search) {
                $query->where('customers.dni', $search)
                      ->orWhere('customers.last_name', 'like', "%$search%");
            })
            ->get();

        return response()->json($customers);
    }

    /**
     * PUNTO 3: Eliminación Lógica
     */
    public function destroy($dni)
    {
        $updated = DB::table('customers')
            ->where('dni', $dni)
            ->where('status', '!=', 'trash')
            ->update(['status' => 'trash']);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Cliente eliminado (lógico)']);
        }

        return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
    }
}