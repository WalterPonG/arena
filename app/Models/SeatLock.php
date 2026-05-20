<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatLock extends Model
{
    protected $table = 'seat_locks';

    protected $fillable = [
        'asiento_id',
	'evento_id',
        'session_id',
        'expires_at'
    ];
}
