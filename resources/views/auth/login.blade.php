@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card bg-dark border-secondary text-white">

            <div class="card-body">

                <h2 class="mb-4 text-warning">
                    Iniciar sesión
                </h2>


		@if ($errors->any())
    				<div class="alert alert-danger">
        			<ul class="mb-0">
            			@foreach ($errors->all() as $error)
                		<li>{{ $error }}</li>
            		@endforeach
        		</ul>
    		</div>
		@endif
                <form method="POST" action="/login">

                    @csrf

                    <div class="mb-3">
                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalidad @enderror">

                    </div>
		    @error('email')
			<div class="text-danger">{{ $message }}</div>
		    @enderror

                    <div class="mb-3">
                        <label>Contraseña</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <button class="btn btn-warning w-100">
                        Entrar
                    </button>

                </form>

		<hr class="border-secondary">

                <p class="text-center mb-0">
                    ¿No tienes cuenta?
                    <a href="/register" class="text-warning">
                        Crear cuenta
                    </a>
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
