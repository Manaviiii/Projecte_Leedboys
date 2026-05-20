<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_accesorios', function (Blueprint $table) {
            $table->foreignId('item_id')->primary()->constrained('items')->cascadeOnDelete();
            $table->unsignedInteger('stock_total');
            // La imagen se añade fuera del Schema porque Laravel no soporta LONGBLOB nativo
        });

        // Añadir la columna imagen como LONGBLOB igual que en la tabla fotos
        DB::statement('ALTER TABLE item_accesorios ADD COLUMN imagen LONGBLOB NULL');
    }

    public function down()
    {
        Schema::dropIfExists('item_accesorios');
    }
};