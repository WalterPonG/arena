@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card bg-dark border-secondary text-white">

            <div class="card-body">

                <h2 class="mb-4 text-warning">
                    Registro
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

                <form method="POST" action="/register">

                    @csrf

                    <div class="mb-3">
                        <label>Nombre</label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Apellido</label>

                        <input
                            type="text"
                            name="apellido"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >
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
                        Crear cuenta
                    </button>

                </form>

		<hr class="border-secondary">

                <p class="text-center mb-0">
                    ¿Ya tienes cuenta?
                    <a href="/login" class="text-warning">
                        Iniciar sesión
                    </a>
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
