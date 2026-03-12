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
        Schema::create('item_packs', function (Blueprint $table) {
            $table->foreignId('item_id')->primary()->constrained('items')->cascadeOnDelete();
            $table->unsignedInteger('numero_zancudos');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_packs');
    }
};
