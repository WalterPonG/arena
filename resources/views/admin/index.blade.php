@extends('layouts.app')

@section('content')

<div class="container py-5">

    <h1 class="mb-4">
        Panel Admin
    </h1>

    <div class="card bg-dark text-white border-secondary">
        <div class="card-body">

            <table class="table table-dark table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Evento</th>
                        <th>Sector</th>
                        <th>Fila</th>
                        <th>Asiento</th>
                        <th>Precio</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($entradas as $entrada)

                        <tr>
                            <td>{{ $entrada->id }}</td>

                            <td>
                                {{ $entrada->user->email }}
                            </td>

                            <td>
                                {{ $entrada->evento->nombre }}
                            </td>

                            <td>
                                {{ $entrada->asiento->sector->nombre }}
                            </td>

                            <td>
                                {{ $entrada->asiento->fila }}
                            </td>

                            <td>
                                {{ $entrada->asiento->numero }}
                            </td>

                            <td>
                                {{ $entrada->precio_pagado }} €
                            </td>

			     <td>
                    		<button class="btn btn-danger btn-sm btn-delete"
                       			data-id="{{ $entrada->id }}">
                        		Eliminar
                    		</button>
                	    </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
