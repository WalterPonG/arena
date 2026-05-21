<h1>🎟️ Compra confirmada</h1>

<p>Gracias por tu compra.</p>

@if($entradas->first()?->evento)
    <h2>{{ $entradas->first()->evento->nombre }}</h2>

    <img src="{{ $entradas->first()->evento->poster_url }}"
         alt="Poster del evento"
         style="max-width:300px; border-radius:8px; margin-bottom:15px;">
@endif

<p>Estas son tus entradas:</p>

<ul>
@foreach($entradas as $entrada)
    <li>
        Sector: {{ $entrada->asiento->sector->nombre }} -
        Fila: {{ $entrada->asiento->fila }} -
        Asiento: {{ $entrada->asiento->numero }}
    </li>
@endforeach
</ul>
