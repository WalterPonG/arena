<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    /**
     * Listar mis entradas
     */
    public function index(Request $request)
    {

	 $entradas = Entrada::where('user_id', auth()->id())
        ->with(['evento', 'asiento'])
        ->latest()
        ->get();

    return view('entradas.index', compact('entradas'));
        /*$entradas = $request->user()
            ->entradas()
            ->with(['evento', 'asiento.sector'])
            ->latest()
            ->get()

	return EntradaResource::collection($entradas);*/
    }

    /**
     * Ver detalle de una entrada
     */
    public function show($id)
    {
        $entrada = Entrada::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['evento', 'asiento.sector'])
            ->firstOrFail();

        return response()->json([
            'data' => $entrada->informacionCompleta(),
        ]);
    }

    public function misEntradas()
{
    $entradas = \App\Models\Entrada::where('user_id', auth()->id())
        ->with(['evento', 'asiento'])
        ->latest()
        ->get();

    return view('entradas.index', compact('entradas'));
}
}
