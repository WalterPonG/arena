<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Sector;
use App\Models\Asiento;
use Illuminate\Http\Request;
use App\Models\EstadoAsiento;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AsientoController extends Controller
{
    /**
     * Obtener asientos disponibles de un evento
     */
    public function porEvento($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        
        // Obtener sectores disponibles
        $sectoresDisponibles = $evento->sectoresDisponibles()->pluck('id');
        
        // Obtener asientos de esos sectores
        $asientos = Asiento::whereIn('sector_id', $sectoresDisponibles)
            ->with('sector')
            ->get()
            ->map(function ($asiento) use ($eventoId) {
                return [
                    'id' => $asiento->id,
                    'sector' => $asiento->sector->nombre,
                    'fila' => $asiento->fila,
                    'numero' => $asiento->numero,
                    'disponible' => $asiento->estaDisponibleParaEvento($eventoId),
                    'precio' => $asiento->sector->precios()
                        ->where('evento_id', $eventoId)
                        ->first()?->precio,
                ];
            });

        return response()->json([
            'data' => $asientos,
        ]);
    }

    /**
     * Obtener asientos de un sector específico para un evento
     */
    public function porSector($eventoId, $sectorId)
    {
        $evento = Evento::findOrFail($eventoId);
        $sector = Sector::findOrFail($sectorId);
        
        // Verificar que el sector esté disponible para el evento
        if (!$evento->sectorEstaDisponible($sectorId)) {
            return response()->json([
                'error' => 'El sector no está disponible para este evento',
            ], 400);
        }

        $asientos = $sector->asientos()
            ->get()
            ->map(function ($asiento) use ($eventoId) {
                return [
                    'id' => $asiento->id,
                    'fila' => $asiento->fila,
                    'numero' => $asiento->numero,
                    'disponible' => $asiento->estaDisponibleParaEvento($eventoId),
                ];
            });

        $precio = $evento->precioDelSector($sectorId);

        return response()->json([
            'data' => [
                'sector' => $sector,
                'precio' => $precio?->precio,
                'asientos' => $asientos,
            ],
        ]);
    }

    public function bloquear(Request $request)
{
    $request->validate([
        'evento_id' => 'required|integer',
        'asiento_id' => 'required|integer',
    ]);

    $eventoId = $request->evento_id;
    $asientoId = $request->asiento_id;

    // limpiar expirados
    EstadoAsiento::where('estado', 'bloqueado')
        ->where('reservado_hasta', '<', now())
        ->delete();

    // comprobar si está bloqueado activo
    $bloqueado = EstadoAsiento::where('evento_id', $eventoId)
        ->where('asiento_id', $asientoId)
        ->where('estado', 'bloqueado')
        ->where('reservado_hasta', '>', now())
        ->exists();

    if ($bloqueado) {
        return response()->json([
            'ok' => false,
            'message' => 'Asiento no disponible'
        ], 409);
    }

    // crear bloqueo o actualizar
    EstadoAsiento::updateOrCreate(
        [
            'evento_id' => $eventoId,
            'asiento_id' => $asientoId,
        ],
        [
            'estado' => 'bloqueado',
            'reservado_hasta' => Carbon::now()->addMinutes(15),
            'user_id' => auth()->id()
        ]
    );

    return response()->json([
        'ok' => true,
        'message' => 'Asiento bloqueado'
    ]);
}
public function liberar(Request $request)
{
    EstadoAsiento::where('evento_id', $request->evento_id)
        ->where('asiento_id', $request->asiento_id)
        ->where('estado', 'bloqueado')
        ->delete();

    return response()->json(['ok' => true]);
}
}
