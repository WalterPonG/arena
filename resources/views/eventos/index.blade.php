@extends('layouts.app')

@section('content')

<h1 class="mb-4 fw-bold">
    Próximos Eventos
</h1>

<div class="row">

    @foreach($eventos as $evento)

        <div class="col-md-4 mb-4">

            <div class="card bg-secondary text-white h-100 shadow">

                <img src="{{ $evento->poster_url }}" class="card-img-top">

                <div class="card-body d-flex flex-column">

                    <h5>{{ $evento->nombre }}</h5>

                    <p>{{ $evento->descripcion_corta }}</p>

                    <a href="/eventos/{{ $evento->id }}"
                       class="btn btn-warning mt-auto">
                        Comprar entradas
                    </a>

                </div>
            </div>

        </div>

    @endforeach

</div>

@endsection
