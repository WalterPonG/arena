<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entrada;

class AdminController extends Controller
{
    public function index()
    {
        // solo admin@test.com
        if (!auth()->user()?->is_admin) {
            abort(403);
        }

        $entradas = Entrada::with([
            'user',
            'evento',
            'asiento.sector'
        ])->latest()->get();

        return view('admin.index', compact('entradas'));
    }

     public function destroyEntrada($id)
{
    $entrada = Entrada::findOrFail($id);
    $entrada->delete();

    return response()->json([
        'message' => 'Entrada eliminada correctamente'
    ]);
}
}
