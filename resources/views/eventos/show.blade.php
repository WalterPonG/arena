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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Selección de asientos</h3>
            </div>

		@php
		$asientos = collect();

		foreach ($evento->precios as $precio) {
    			foreach ($precio->sector->asientos as $asiento) {
        			$asientos->push((object)[
            			'id' => $asiento->id,
            			'fila' => $asiento->fila,
            			'numero' => $asiento->numero,
            			'sector' => $precio->sector->nombre,
            			'precio' => $precio->precio,
        			]);
    			}
		}

		$mapa = $asientos->groupBy('fila');
		@endphp


		@php
		$asientos = collect();

	foreach ($evento->precios as $precio) {
    	foreach ($precio->sector->asientos as $asiento) {
        $asientos->push((object)[
            'id' => $asiento->id,
            'fila' => $asiento->fila,
            'numero' => $asiento->numero,
            'sector' => $precio->sector->nombre,
            'precio' => $precio->precio,
        	]);
    		}
		}

		$mapa = $asientos->groupBy('fila');
		@endphp

            {{-- ========================= --}}
            {{-- 🔥 MAPA DE ASIENTOS --}}
            {{-- ========================= --}}

            @php
                $asientos = collect();

                foreach ($evento->precios as $precio) {
                    foreach ($precio->sector->asientos as $asiento) {
                        $asientos->push((object)[
                            'id' => $asiento->id,
                            'fila' => $asiento->fila,
                            'numero' => $asiento->numero,
                            'sector' => $precio->sector->nombre,
                            'precio' => $precio->precio,
                        ]);
                    }
                }

                $asientosPorFila = $asientos->groupBy('fila');
            @endphp

            <div class="seat-map">

                @foreach($asientosPorFila as $fila => $asientosFila)

                    <div class="seat-row seat-row-{{ $fila }}">

                        {{-- 🔥 etiqueta de fila --}}
                        <div class="seat-row-label">
                            {{ $fila }}
                        </div>

                        {{-- 🔥 grid de asientos --}}
                        <div class="seat-row-seats">

                            @foreach($asientosFila as $asiento)

                                <button
                                    class="seat asiento-btn"
                                    data-id="{{ $asiento->id }}"
                                    data-sector="{{ $asiento->sector }}"
                                    data-fila="{{ $asiento->fila }}"
                                    data-numero="{{ $asiento->numero }}"
                                    data-precio="{{ $asiento->precio }}"
                                >
                                    {{ $asiento->numero }}
                                </button>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

{{-- 🔥 DATOS GLOBAL JS --}}
<script>
    document.body.dataset.eventoId = "{{ $evento->id }}";
    document.body.dataset.auth = "{{ auth()->check() ? 1 : 0 }}";
</script>

@endsection
