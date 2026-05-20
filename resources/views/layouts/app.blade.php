<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Roig Arena</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Vite --}}
    @vite(['resources/js/app.js'])
</head>

<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">

      <div class="modal-header">
        <h5 class="modal-title">Inicia sesión</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form method="POST" action="/login">
          @csrf

          <input class="form-control mb-2" name="email" placeholder="Email">
          <input class="form-control mb-2" name="password" type="password" placeholder="Password">

          <button class="btn btn-warning w-100">Entrar</button>
        </form>

      </div>

    </div>
  </div>
</div>


<body class="bg-dark text-white" data-evento-id="@yield('$evento->id')">

    {{-- NAVBAR GLOBAL --}}
    @include('partials.navbar')

    {{-- CONTENIDO --}}
    <main class="container py-4">
        @yield('content')
    </main>

</body>
</html>
