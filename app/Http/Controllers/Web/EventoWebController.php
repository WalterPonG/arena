<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evento;

class EventoWebController extends Controller
{
    public function index()
    {
    $eventos = Evento::orderBy('fecha')->get();

    $user = auth()->user();

    $discountActive = false;
    $discountSeconds = 0;

    if ($user && $user->is_new_user && $user->created_at->gt(now()->subMinutes(5))) {
        $discountActive = true;
        $discountSeconds = 300 - $user->created_at->diffInSeconds(now());
    }

    return view('eventos.index', compact(
        'eventos',
        'discountActive',
        'discountSeconds'
    ));


    }

    public function show(Evento $evento) {
	$evento->load('precios.sector.asientos');

	return view('eventos.show', compact('evento'));
    }
}
