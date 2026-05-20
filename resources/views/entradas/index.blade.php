@extends('layouts.app')

@section('content')
<div class="container text-white">

    <h2 class="mb-4">🎫 Mis entradas</h2>

    @if($entradas->isEmpty())
        <div class="alert alert-warning">
            No tienes entradas todavía.
        </div>
    @endif

    <div class="row g-3">

        @foreach($entradas as $entrada)
            <div class="col-md-6">

                <div class="card bg-dark border-light text-white shadow">

                    <div class="card-body">

                        <h5 class="card-title">
                            🎤 {{ $entrada->evento->nombre ?? 'Evento' }}
                        </h5>

                        <p class="mb-1">
                            📅 {{ $entrada->evento->fecha ?? 'Sin fecha' }}
                        </p>

                        <hr>

                        <p class="mb-1">
                            💺 Sector: {{ $entrada->asiento->sector->nombre ?? '-' }}
                        </p>

                        <p class="mb-1">
                            Fila: {{ $entrada->asiento->fila ?? '-' }}
                            - Asiento: {{ $entrada->asiento->numero ?? '-' }}
                        </p>

                        <p class="mb-2">
                            💰 {{ $entrada->precio_pagado }} €
                        </p>

                        <div class="bg-secondary p-2 rounded">
                            🔐 Código: <strong>{{ $entrada->codigo_qr }}</strong>
                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

</div>
@endsection

