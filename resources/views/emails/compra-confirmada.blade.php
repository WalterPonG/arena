<h1>🎟️ Compra confirmada</h1>

<p>Gracias por tu compra.</p>

<p>Estas son tus entradas:</p>

<ul>
@foreach($entradas as $entrada)
    <li>
        Sector: {{ $entrada->sector }} -
        Fila: {{ $entrada->fila }} -
        Asiento: {{ $entrada->numero }}
    </li>
@endforeach
</ul>
