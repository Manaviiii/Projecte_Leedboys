<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_trajes', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->enum('tipo_traje', ['zancos', 'sin_zancos']);
            $table->enum('genero', ['chico', 'chica', 'unisex'])->default('unisex');
            $table->unsignedInteger('stock_total');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_trajes');
    }
};
