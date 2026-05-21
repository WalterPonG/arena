<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary">
    <div class="container">

        {{-- BRAND --}}
        <a class="navbar-brand fw-bold text-warning" href="/">
            Roig Arena
        </a>

        {{-- DERECHA --}}
        <div class="ms-auto d-flex align-items-center gap-2 position-relative">

	    @if(auth()->check() && auth()->user()->email === 'admin@test.com')
		<a href="/admin" class="btn btn-warning me-2">
			Admin
		</a>
	    @endif

            {{-- LOGOUT --}}
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            @endauth
            
	    
    			<a class="nav-link text-white" href="/mis-entradas">
        		Mis entradas
    		</a>
	  
            {{-- CARRITO --}}
            <button class="btn btn-warning position-relative" id="boton-carrito">
                🛒
                <span id="contador-carrito"
                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            </button>

            {{-- DROPDOWN CARRITO --}}
            <div id="dropdown-carrito"
                 class="card bg-dark text-white border-secondary shadow-lg p-3"
                 style="width:320px; position:absolute; right:0; top:60px; display:none; z-index:999;">

                <h5 class="mb-3">Mi carrito</h5>

                <div id="carrito-items"></div>

                <hr>

                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <span id="carrito-total">0 €</span>
                </div>

                <button id="btn-comprar" class="btn btn-warning w-100 mt-3">
                    Comprar
                </button>

            </div>

        </div>

    </div>
</nav>
