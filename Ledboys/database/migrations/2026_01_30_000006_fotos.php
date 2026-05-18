<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idTraje')->constrained('item_trajes')->cascadeOnDelete();
            $table->boolean('principal')->default(false);
            $table->string('nombre');
            $table->integer('orden');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE fotos ADD COLUMN imagen LONGBLOB');
    }

    public function down()
    {
        Schema::dropIfExists('fotos');
    }
};