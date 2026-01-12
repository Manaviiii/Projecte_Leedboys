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
        Schema::create('trajes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // nombre del traje
            $table->decimal('precio', 10, 2); // precio con hasta 10 dígitos y 2 decimales
            $table->integer('stock');
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
        Schema::dropIfExists('trajes');
    }
};
