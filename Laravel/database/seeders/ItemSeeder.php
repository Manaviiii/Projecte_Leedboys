<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            // TRAJES
            [
                'id' => 1,
                'nombre' => 'Marshmello',
                'tipo' => 'traje',
                'precio' => 150,
                'activo' => true,
            ],
            [
                'id' => 2,
                'nombre' => 'Robot LED',
                'tipo' => 'traje',
                'precio' => 200,
                'activo' => true,
            ],
            [
                'id' => 3,
                'nombre' => 'Árbol Animado',
                'tipo' => 'traje',
                'precio' => 100,
                'activo' => true,
            ],

            // ACCESORIOS
            [
                'id' => 4,
                'nombre' => 'Pistola de burbujas',
                'tipo' => 'accesorio',
                'precio' => 50,
                'activo' => true,
            ],
            [
                'id' => 5,
                'nombre' => 'Cañón confeti',
                'tipo' => 'accesorio',
                'precio' => 50,
                'activo' => true,
            ],

            // PACK
            [
                'id' => 6,
                'nombre' => 'Pack LED',
                'tipo' => 'pack',
                'precio' => 300,
                'activo' => true,
            ],
        ]);
    }
}
