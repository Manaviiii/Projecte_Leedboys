<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('ubicacion')->nullable();
            $table->decimal('total_precio', 8, 2)->default(0);
            $table->enum('estado', ['borrador', 'reservado', 'pagado', 'cancelado'])->default('borrador');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
};