<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Entrada;
use App\Models\SeatLock;
use App\Models\CarritoItem;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|integer',
            'carrito' => 'required|array'
        ]);

        $eventoId = $request->evento_id;
        $carrito = $request->carrito;

        DB::beginTransaction();

        $entradas = [];

        foreach ($carrito as $item) {

            $asientoId = $item['asiento_id'];
            $lock = SeatLock::where('asiento_id', $asientoId)
	    ->where('session_id', session()->getId())
            ->first();

            if (!$lock) {
                return response()->json([
                    'ok' => false,
                    'message' => "Asiento no bloqueado"
                ], 409);
            }

            $codigo = Str::uuid()->toString();

            $entrada = Entrada::create([
                'user_id' => auth()->id(),
                'evento_id' => $eventoId,
                'asiento_id' => $item['asiento_id'],
                'precio_pagado' => $item['precio'],
                'codigo_qr' => $codigo
            ]);

            $lock->update([
                'estado' => 'vendido'
            ]);

            $entradas[] = $entrada;
        }

        SeatLock::where('session_id', session()->getId())->delete();
        CarritoItem::where('user_id', auth()->id())->delete();

        DB::commit();

        return response()->json([
            'ok' => true,
            'entradas' => $entradas
        ]);
    }
}
