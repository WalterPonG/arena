@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="row">

        {{-- IZQUIERDA --}}
        <div class="col-md-8">

            {{-- POSTER --}}
            <img
                src="{{ $evento->poster_url }}"
                class="img-fluid rounded shadow mb-4"
                alt="{{ $evento->nombre }}"
            >

            {{-- INFO --}}
            <h1 class="fw-bold mb-3">
                {{ $evento->nombre }}
            </h1>

            <p class="text-warning fs-5">
                {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                —
                {{ $evento->hora }}
            </p>

            <p class="mb-4">
                {{ $evento->descripcion_larga }}
            </p>

            <hr>

            {{-- TÍTULO --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Selección de asientos</h3>
            </div>

            {{-- ASIENTOS --}}
            @foreach($evento->precios as $precio)

                <div class="mb-5">

                    <h4 class="text-warning mb-3">
                        {{ $precio->sector->nombre }}
                        —
                        {{ number_format($precio->precio, 2) }} €
                    </h4>

                    <div class="d-flex flex-wrap gap-2">

                        @foreach($precio->sector->asientos->take(40) as $asiento)

                            <button
                                class="btn btn-outline-light asiento-btn"
                                data-id="{{ $asiento->id }}"
                                data-evento="{{ $evento->id }}"
                                data-sector="{{ $precio->sector->nombre }}"
                                data-fila="{{ $asiento->fila }}"
                                data-numero="{{ $asiento->numero }}"
                                data-precio="{{ $precio->precio }}"
                            >
                                {{ $asiento->fila }}-{{ $asiento->numero }}
                            </button>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

{{-- 🔥 IMPORTANTE: datos globales para JS --}}
<script>
    document.body.dataset.eventoId = "{{ $evento->id }}";
    document.body.dataset.auth = "{{ auth()->check() ? 1 : 0 }}";
</script>

@endsection
