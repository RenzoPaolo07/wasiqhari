<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Arroz, Paracetamol
            $table->enum('categoria', ['Alimentos', 'Medicinas', 'Ropa', 'Equipamiento', 'Otros']);
            $table->integer('cantidad');
            $table->string('unidad'); // Ej: kg, latas, cajas, unidades
            $table->date('fecha_vencimiento')->nullable();
            $table->text('descripcion')->nullable(); // Detalles extra
            $table->string('estado')->default('Disponible'); // Disponible, Agotado, Por Vencer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};