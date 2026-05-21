<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Web\EventoWebController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\EntradaController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AdminController;

Route::get('/test-mail', function () {
    Mail::raw('Esto es una prueba', function ($message) {
        $message->to('tu_correo_real@gmail.com')
                ->subject('Test Roig Arena');
    });

    return 'mail enviado';
});

/*Route::get('/', function () {
    return view('eventos.index');
});*/

Route::get('/', function () {
	if (auth()->check()) {
        return redirect('/eventos');
    }
     return redirect('/login');
});

Route::delete('/admin/entradas/{id}', [AdminController::class, 'destroyEntrada'])
    ->middleware('auth');
Route::get('/eventos', [EventoWebController::class, 'index']);
// Route::get('/', [EventoWebController::class, 'index']);
Route::get('/eventos/{evento}', [EventoWebController::class, 'show']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/mis-entradas', [EntradaController::class, 'misEntradas'])
    ->middleware('auth');

// proteger checkout
Route::middleware('auth')->group(function () {
        Route::get('/mis-entradas', [EntradaController::class, 'misEntradas']);

    Route::post('/checkout', [CheckoutController::class, 'store']);

});


Route::middleware('auth')->group(function () {

    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito/add', [CarritoController::class, 'add']);
    Route::delete('/carrito/{asientoId}', [CarritoController::class, 'remove']);
    Route::delete('/carrito', [CarritoController::class, 'clear']);

});


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::post('/carrito/sync', [CarritoController::class, 'sync'])
    ->middleware('auth');


Route::get('/admin', [AdminController::class, 'index'])
	->middleware('auth');
