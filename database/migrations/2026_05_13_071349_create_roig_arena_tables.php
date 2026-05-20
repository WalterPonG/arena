<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {	// tabla de sectores (zona del estadio)
        Schema::create('sectores', function (Blueprint $table) {
            $table->id();
	    $table->string('nombre');
	    $table->text('descripcion');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

	Schema::create('asientos', function (Blueprint $table) {
		$table->id();
		$table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
		$table->string('fila');
		$table->integer('numero');
		$table->unique(['sector_id', 'fila', 'numero']);
		$table->timestamps();
	});

	 // Tabla de eventos
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion_corta', 255); // Para listados
            $table->text('descripcion_larga'); // Para la página del evento
            $table->string('poster_url')->nullable(); // URL de la imagen
            $table->date('fecha')->unique(); // Solo un evento por día
            $table->time('hora')->nullable(); // Hora del evento
            $table->timestamps();
            $table->softDeletes(); // Borrado lógico
        });

        // Tabla de precios (precio por sector en cada evento)
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->decimal('precio', 10, 2); // Precio con 2 decimales
            $table->boolean('disponible')->default(true); // Control por evento (true = disponible, false = cerrado para este evento)
            $table->unique(['evento_id', 'sector_id']); // Un precio por sector/evento
            $table->timestamps();
        });

        // Tabla de estado de asientos (control de reservas)
        Schema::create('estado_asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('asiento_id')->constrained('asientos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('estado', ['bloqueado', 'vendido']);
            $table->timestamp('reservado_hasta')->nullable(); // Temporizador de reserva
            $table->unique(['evento_id', 'asiento_id']); // Un estado por asiento/evento
            $table->timestamps();
        });

        // ============================================
        // 3. VENTAS DEFINITIVAS
        // ============================================
        
        // Tabla de entradas (ventas confirmadas)
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('asiento_id')->constrained('asientos')->onDelete('cascade');
            $table->decimal('precio_pagado', 10, 2); // Precio al momento de la compra
            $table->string('codigo_qr')->unique(); // Código QR único para validación
            $table->unique(['evento_id', 'asiento_id']); // Una entrada por asiento/evento
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	// Eliminar en orden inverso por las dependencias
        Schema::dropIfExists('entradas');
        Schema::dropIfExists('estado_asientos');
        Schema::dropIfExists('precios');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('asientos');
        Schema::dropIfExists('sectores');
    }
};
