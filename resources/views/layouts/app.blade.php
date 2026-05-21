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


	{{-- 🔥 API URL GLOBAL --}}
    <script>
        window.API_URL = "{{ env('API_URL') }}";
    </script>

    {{-- Vite --}}
    @vite(['resources/js/app.js'])
    @if(request()->is('admin*'))
        @vite(['resources/js/admin.js'])
    @endif
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


		{{-- 🔥 BANNER DESCUENTO GLOBAL --}}
    @if(isset($discountActive) && $discountActive)

        <div id="discount-banner" style="
            background:#ffc107;
            color:#000;
            padding:10px;
            text-align:center;
            font-weight:bold;
        ">
            🎉 Tienes 50% de descuento activo —
            <span id="discount-timer"></span>
        </div>

    @endif


    {{-- CONTENIDO --}}
    <main class="container py-4">
        @yield('content')
    </main>

	<div id="loadingOverlay" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);
    z-index:9999;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    color:white;
">
    <h3>Procesando compra...</h3>

    <div style="width:300px; height:10px; background:#444; border-radius:5px; overflow:hidden; margin-top:15px;">
        <div id="progressBar" style="width:0%; height:100%; background:#ffc107;"></div>
    </div>
</div>

</body>
</html>
