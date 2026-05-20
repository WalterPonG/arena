<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evento;

class EventoWebController extends Controller
{
    public function index()
    {
        $eventos = Evento::orderBy('fecha')->get();

        return view('eventos.index', compact('eventos'));
    }

    public function show(Evento $evento) {
	$evento->load('precios.sector.asientos');

	return view('eventos.show', compact('evento'));
    }
}
