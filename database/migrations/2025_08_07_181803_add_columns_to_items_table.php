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
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('estoque_minimo')->default(0)->after('unidade');
            $table->decimal('preco_unitario', 8, 2)->nullable()->after('estoque_minimo');
            $table->string('localizacao')->nullable()->after('preco_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['estoque_minimo', 'preco_unitario', 'localizacao']);
        });
    }
};

