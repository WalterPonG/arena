<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeatLock;
use App\Models\Entrada;
class SeatLockController extends Controller
{
    public function lock(Request $request)
    {
        $request->validate([
            'asiento_id' => 'required',
	    'evento_id' => 'required|exists:eventos,id'
        ]);



        $asientoId = $request->asiento_id;
        $sessionId = session()->getId();

        // limpiar expirados
        SeatLock::where('expires_at', '<', now())->delete();

        // verificar si está bloqueado por otro usuario
		$exists = SeatLock::where('asiento_id', $asientoId)
    ->where('evento_id', $request->evento_id)
    ->where('session_id', '!=', $sessionId)
    ->where('expires_at', '>', now())
    ->exists();
        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asiento ya reservado'
            ], 409);
        }

     SeatLock::updateOrCreate(
    	['asiento_id' => $request->asiento_id, 'session_id' => session()->getId()],
    	[
        	'evento_id' => $request->evento_id,
        	'expires_at' => now()->addMinutes(1)
        ]
	);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function unlock(Request $request)
    {
        SeatLock::where('asiento_id', $request->asiento_id)
            ->where('session_id', session()->getId())
            ->delete();

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function index()
{
    // limpiar expirados
    SeatLock::where('expires_at', '<', now())->delete();

    $locks = SeatLock::where('expires_at', '>', now())
        ->get(['asiento_id', 'session_id', 'expires_at']);

    return response()->json($locks);
}

public function locks()
{
    SeatLock::where('expires_at', '<', now())->delete();
    return response()->json(
        SeatLock::pluck('asiento_id')
    );
}

public function unlockAll()
{
    SeatLock::where('session_id', session()->getId())->delete();

    return response()->json(['ok' => true]);
}

public function state($eventoId)
{
    SeatLock::where('expires_at', '<', now())->delete();

    $locked = SeatLock::where('evento_id', $eventoId)
        ->where('expires_at', '>', now())
        ->pluck('asiento_id');

    $sold = Entrada::where('evento_id', $eventoId)
        ->pluck('asiento_id');

    return response()->json([
        'locked' => $locked,
        'sold' => $sold
    ]);
}

}
