<?php
namespace App\Services;

use App\Models\EstadoAsiento;
use App\Models\Entrada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompraConfirmadaMail;
class CompraService
{
    /**
     * Procesar compra de múltiples reservas
     */
    public function procesarCompra(array $reservasIds, $user)
    {
        $entradas = [];

        DB::beginTransaction();
        try {
            foreach ($reservasIds as $reservaId) {
                $reserva = $this->obtenerReserva($reservaId, $user->id);
                
                // Verificar expiración
                $this->verificarNoExpirada($reserva);
                
                // Obtener precio
                $precio = $this->obtenerPrecio($reserva);
                
                // Marcar como vendido
                $reserva->marcarComoVendido();
                
                // Crear entrada
                $entrada = $this->crearEntrada($reserva, $precio, $user->id);
                
                $entradas[] = $entrada;
            }

            DB::commit();



		$entradasFinales = Entrada::with(['evento', 'asiento.sector'])
    		->whereIn('id', array_map(fn($e) => $e->id, $entradas))
    		->get();
		$mailTo = env('MAIL_TEST_TO');
		Mail::to($mailTo)->send(
    		new CompraConfirmadaMail($entradasFinales)
		);

	    return $entradasFinales;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtener una reserva del usuario
     */
    private function obtenerReserva($reservaId, $userId)
    {
        return EstadoAsiento::where('id', $reservaId)
            ->where('user_id', $userId)
            ->where('estado', 'bloqueado')
            ->with(['evento', 'asiento.sector'])
            ->firstOrFail();
    }

    /**
     * Verificar que la reserva no haya expirado
     */
    private function verificarNoExpirada($reserva)
    {
        if ($reserva->haExpirado()) {
            throw new \Exception('Una de las reservas ha expirado');
        }
    }

    /**
     * Obtener el precio del sector para el evento
     */
    private function obtenerPrecio($reserva)
    {
        $precio = $reserva->evento->precioDelSector($reserva->asiento->sector_id);
        
        if (!$precio) {
            throw new \Exception('No se encontró el precio para el sector');
        }
        
        return $precio;
    }

    /**
     * Crear la entrada
     */
    private function crearEntrada($reserva, $precio, $userId)
    {
        return Entrada::create([
            'user_id' => $userId,
            'evento_id' => $reserva->evento_id,
            'asiento_id' => $reserva->asiento_id,
            'precio_pagado' => $precio->precio,
        ]);
    }
}
