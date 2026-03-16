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
            ['id' => 1,  'nombre' => 'Daft Punk',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/daft_punk.jpg',        'activo' => true],
            ['id' => 2,  'nombre' => 'Iluminati',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/iluminati.jpg',        'activo' => true],
            ['id' => 3,  'nombre' => 'Bad Bunny x Rauw',  'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Bad_bunny_x_rauw.jpg', 'activo' => true],
            ['id' => 4,  'nombre' => 'Mariachis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/mariachis.jpg',        'activo' => true],
            ['id' => 5,  'nombre' => 'Flower Power',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/flower_power.jpg',     'activo' => true],
            ['id' => 6,  'nombre' => 'Árboles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/arboles.jpg',          'activo' => true],
            ['id' => 7,  'nombre' => 'Anubis',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/anubis.jpg',           'activo' => true],
            ['id' => 8,  'nombre' => 'Gladiadores',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/gladiadores.jpg',      'activo' => true],
            ['id' => 9,  'nombre' => 'Motomamis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/motomamis.jpg',        'activo' => true],
            ['id' => 10, 'nombre' => 'Disco Girls',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/disco_girls.jpg',      'activo' => true],
            ['id' => 11, 'nombre' => 'Ángeles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/angeles.jpg',          'activo' => true],
            ['id' => 12, 'nombre' => 'Future Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/future_girls.jpg',     'activo' => true],

            // ACCESORIOS
            ['id' => 13, 'nombre' => 'Pistola de burbujas', 'tipo' => 'accesorio', 'precio' => 50,  'imagen' => null, 'activo' => true],
            ['id' => 14, 'nombre' => 'Cañón confeti',       'tipo' => 'accesorio', 'precio' => 50,  'imagen' => null, 'activo' => true],

            // PACK
            ['id' => 15, 'nombre' => 'Pack LED', 'tipo' => 'pack', 'precio' => 300, 'imagen' => null, 'activo' => true],
        ]);
    }
}
