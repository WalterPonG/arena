<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nombre', 'apellido', 'email', 'password'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     // ============================================
    // RELACIONES
    // ============================================

    /**
     * Un usuario tiene muchas reservas (estados de asientos)
     */
    public function reservas()
    {
        return $this->hasMany(EstadoAsiento::class);
    }

    /**
     * Un usuario tiene muchas entradas compradas
     */
    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    // ============================================
    // MÉTODOS ÚTILES
    // ============================================

    /**
     * Obtener reservas activas (no expiradas)
     */
    public function reservasActivas()
    {
        return $this->reservas()
            ->where('estado', 'bloqueado')
            ->where('reservado_hasta', '>', now())
            ->get();
    }

    /**
     * Obtener entradas válidas (eventos futuros)
     */
    public function entradasValidas()
    {
        return $this->entradas()
            ->whereHas('evento', function ($q) {
                $q->where('fecha', '>=', now()->toDateString());
            })
            ->get();
    }

    /**
     * Verificar si tiene una reserva para un evento específico
     */
    public function tieneReservaEnEvento($eventoId): bool
    {
        return $this->reservas()
            ->where('evento_id', $eventoId)
            ->exists();
    }

    /**
     * Verificar si tiene una entrada para un evento específico
     */
    public function tieneEntradaEnEvento($eventoId): bool
    {
        return $this->entradas()
            ->where('evento_id', $eventoId)
            ->exists();
    }
}
