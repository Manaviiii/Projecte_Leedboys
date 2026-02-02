<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventoItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('evento_items')->insert([
            // Evento 1 → traje solo
            [
                'evento_id' => 1,
                'item_id' => 1, // Marshmello
                'cantidad' => 1,
                'precio_unitario' => 150,
            ],

            // Evento 2 → traje + accesorio
            [
                'evento_id' => 2,
                'item_id' => 2, // Robot LED
                'cantidad' => 1,
                'precio_unitario' => 200,
            ],
            [
                'evento_id' => 2,
                'item_id' => 4, // Pistola burbujas
                'cantidad' => 1,
                'precio_unitario' => 50,
            ],

            // Evento 3 → pack + elección de trajes
            [
                'evento_id' => 3,
                'item_id' => 6, // Pack LED
                'cantidad' => 1,
                'precio_unitario' => 300,
            ],
            [
                'evento_id' => 3,
                'item_id' => 1, // Marshmello
                'cantidad' => 1,
                'precio_unitario' => 0,
            ],
            [
                'evento_id' => 3,
                'item_id' => 2, // Robot LED
                'cantidad' => 1,
                'precio_unitario' => 0,
            ],
        ]);
    }
}
