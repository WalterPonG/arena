<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use App\Http\Requests\RegisterRequest;
// use App\Http\Requests\LoginRequests;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN VIEW
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER VIEW
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    /*public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
		return back()->withErrors([
            		'email' => 'Credenciales incorrectas'
        	])->onlyInput('email');
        }
		$request->session()->regenerate();

            return redirect('/');
    }*/


	public function login(Request $request)
	{
    	if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'email' => 'Credenciales incorrectas'
        ])->onlyInput('email');
    	}

    	$request->session()->regenerate();

	if (auth()->user()->is_admin) {
        return redirect('/admin');
        }

    	return redirect('/');
	}

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {

	$user = User::create([
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        'email' => $request->email,
        'password' => Hash::make($request->password),
	'is_new_user' => true,
    	]);
	/*    $data = $request->validate([
            'nombre' => ['required', 'string', 'min:1'],
            'apellido' => ['required', 'string', 'min:1'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);*/
	
        /*$user = User::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);*/

	

        Auth::login($user);

        return redirect('/');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function user()
{
    return response()->json(auth()->user());
}
}
