<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooperados', function (Blueprint $table) {
            // Adicionar novos campos específicos
            $table->date('data_nascimento')->nullable()->after('tipo_pessoa');
            $table->date('data_constituicao')->nullable()->after('data_nascimento');
        });

        // Migrar dados existentes para os novos campos
        DB::statement("
            UPDATE cooperados 
            SET data_nascimento = data_referencia 
            WHERE tipo_pessoa = 'PF'
        ");
        
        DB::statement("
            UPDATE cooperados 
            SET data_constituicao = data_referencia 
            WHERE tipo_pessoa = 'PJ'
        ");

        // Remover campo antigo
        Schema::table('cooperados', function (Blueprint $table) {
            $table->dropColumn('data_referencia');
        });

        // Adicionar índices para os novos campos
        Schema::table('cooperados', function (Blueprint $table) {
            $table->index('data_nascimento');
            $table->index('data_constituicao');
        });
    }

    public function down(): void
    {
        Schema::table('cooperados', function (Blueprint $table) {
            // Remover índices
            $table->dropIndex(['data_nascimento']);
            $table->dropIndex(['data_constituicao']);
        });

        Schema::table('cooperados', function (Blueprint $table) {
            // Adicionar campo antigo de volta
            $table->date('data_referencia')->after('tipo_pessoa');
        });

        // Migrar dados de volta
        DB::statement("
            UPDATE cooperados 
            SET data_referencia = COALESCE(data_nascimento, data_constituicao)
        ");

        // Remover novos campos
        Schema::table('cooperados', function (Blueprint $table) {
            $table->dropColumn(['data_nascimento', 'data_constituicao']);
        });

        // Readicionar índice antigo
        Schema::table('cooperados', function (Blueprint $table) {
            $table->index('data_referencia');
        });
    }
};
