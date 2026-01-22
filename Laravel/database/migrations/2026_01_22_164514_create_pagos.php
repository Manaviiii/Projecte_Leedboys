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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->nullOnDelete();
            $table->foreignId('residencia_id')->nullable()->constrained('residencias')->nullOnDelete();
            $table->decimal('amount', 8, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'fallido', 'reembolsado']);
            $table->string('stripe_payment_intent_id')->nullable();
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
        Schema::dropIfExists('pagos');
    }
};
