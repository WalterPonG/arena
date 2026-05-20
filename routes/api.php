<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SeatLockController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\SeatStateController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// Autenticación

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/asientos/bloquear', [AsientoController::class, 'bloquear']);
Route::post('/seat/lock', [SeatLockController::class, 'lock']);
Route::post('/seat/unlock', [SeatLockController::class, 'unlock']);
Route::get('/seat/locks', [SeatLockController::class, 'index']);

Route::post('/checkout', [CheckoutController::class, 'store']);
Route::post('/checkout/manual', [CheckoutController::class, 'checkout']);
// Eventos (público)
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/{id}', [EventoController::class, 'show']);

// Sectores (público)
Route::get('/sectores', [SectorController::class, 'index']);

// Asientos (público)
Route::get('/eventos/{eventoId}/asientos', [AsientoController::class, 'porEvento']);
Route::get('/eventos/{eventoId}/sectores/{sectorId}/asientos', [AsientoController::class, 'porSector']);

// ============================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ============================================

Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Reservas (carrito)
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']);
    
    // Compras
    Route::post('/compras', [CompraController::class, 'store']);
    
    // Entradas
    Route::get('/entradas', [EntradaController::class, 'index']);
    Route::get('/entradas/{id}', [EntradaController::class, 'show']);
});

// ============================================
// RUTAS DE ADMINISTRADOR
// ============================================

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    // Eventos (CRUD completo)
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);
    
    // Sectores (CRUD completo)
    Route::post('/sectores', [SectorController::class, 'store']);
    Route::put('/sectores/{id}', [SectorController::class, 'update']);
    Route::delete('/sectores/{id}', [SectorController::class, 'destroy']);
});

Route::post('/seat/unlock-all', [SeatLockController::class, 'unlockAll']);

Route::post('/carrito/sync', [CarritoController::class, 'sync']);

Route::get('/seat/state/{eventoId}', [SeatLockController::class, 'state']);
