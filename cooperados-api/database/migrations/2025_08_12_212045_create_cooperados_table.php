<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperados', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome', 255);
            $table->string('documento', 18)->unique();
            $table->enum('tipo_pessoa', ['PF', 'PJ']);
            $table->date('data_referencia');
            $table->decimal('renda_faturamento', 15, 2);
            $table->string('telefone', 15);
            $table->string('email', 254)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para performance
            $table->index('nome');
            $table->index('tipo_pessoa');
            $table->index('data_referencia');
            $table->index('renda_faturamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperados');
    }
};
