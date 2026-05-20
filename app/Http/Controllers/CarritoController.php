<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoItem;

class CarritoController extends Controller
{
    /*
    |----------------------------------------
    | OBTENER CARRITO
    |----------------------------------------
    */
    public function index()
    {
        return response()->json(
            CarritoItem::where('user_id', auth()->id())->get()
        );
    }

    /*
    |----------------------------------------
    | AÑADIR ITEM
    |----------------------------------------
    */
    public function add(Request $request)
    {
        $data = $request->validate([
            'evento_id' => 'required|integer',
            'asiento_id' => 'required|integer',
            'sector' => 'required',
            'fila' => 'required',
            'numero' => 'required',
            'precio' => 'required'
        ]);

        $item = CarritoItem::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'evento_id' => $data['evento_id'],
                'asiento_id' => $data['asiento_id'],
            ],
            $data
        );

        return response()->json($item);
    }

    /*
    |----------------------------------------
    | ELIMINAR ITEM
    |----------------------------------------
    */
    public function remove($asientoId)
    {
        CarritoItem::where('user_id', auth()->id())
            ->where('asiento_id', $asientoId)
            ->delete();

        return response()->json(['ok' => true]);
    }

    /*
    |----------------------------------------
    | LIMPIAR CARRITO
    |----------------------------------------
    */
    public function clear()
    {
        CarritoItem::where('user_id', auth()->id())->delete();

        return response()->json(['ok' => true]);
    }
    public function sync(Request $request)
{
    $request->validate([
        'evento_id' => 'required|integer',
        'carrito' => 'required|array'
    ]);

    CarritoItem::where('user_id', auth()->id())->delete();

    foreach ($request->carrito as $item) {

        CarritoItem::create([
            'user_id' => auth()->id(),
            'evento_id' => $request->evento_id,
            'asiento_id' => $item['asiento_id'],
            'sector' => $item['sector'],
            'fila' => $item['fila'],
            'numero' => $item['numero'],
            'precio' => $item['precio'],
        ]);
    }

    return response()->json([
        'ok' => true
    ]);
}


}
