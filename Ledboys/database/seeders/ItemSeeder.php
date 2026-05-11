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
            ['id' => 2,  'nombre' => 'Iluminati',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/iluminati.jpg',       'activo' => true],
            ['id' => 3,  'nombre' => 'Bad Bunny x Rauw',  'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Bad_bunny_x_rauw.jpg','activo' => true],
            ['id' => 4,  'nombre' => 'Mariachis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/mariachis.jpg',       'activo' => true],
            ['id' => 5,  'nombre' => 'Flower Power',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/flower_power.jpg',    'activo' => true],
            ['id' => 6,  'nombre' => 'Árboles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/arboles.jpg',         'activo' => true],
            ['id' => 7,  'nombre' => 'Anubis',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/anubis.jpg',          'activo' => true],
            ['id' => 8,  'nombre' => 'Gladiadores',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/gladiadores.jpg',     'activo' => true],
            ['id' => 9,  'nombre' => 'Motomamis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/motomamis.jpg',       'activo' => true],
            ['id' => 10, 'nombre' => 'Disco Girls',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/disco_girls.jpg',     'activo' => true],
            ['id' => 11, 'nombre' => 'Ángeles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/angeles.jpg',         'activo' => true],
            ['id' => 12, 'nombre' => 'Future Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/future_girls.jpg',    'activo' => true],
            ['id' => 13, 'nombre' => 'Circus',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Circus.jpg',    'activo' => true],
            ['id' => 14, 'nombre' => 'Wonderland',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Wonderland.jpg',    'activo' => true],
            ['id' => 15, 'nombre' => 'Golden Angels',     'tipo' => 'traje', 'precio' => 300, 'imagen' => 'images/Golden_Angels.jpg',    'activo' => true],
            ['id' => 16, 'nombre' => 'The Mask',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/The_Mask.jpg',    'activo' => true],
            ['id' => 17, 'nombre' => 'The Joker',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/The_Joker.jpg',    'activo' => true],
            ['id' => 18, 'nombre' => 'Steampunk',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Steampunk.jpg',    'activo' => true],
            ['id' => 19, 'nombre' => 'El Grinch',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/El_Grinch.jpg',    'activo' => true],
            ['id' => 20, 'nombre' => 'Neonik Boys',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Neonik_Boys.jpg',    'activo' => true],
            ['id' => 21, 'nombre' => 'Space Girls',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Space_Girls.jpg',    'activo' => true],
            ['id' => 22, 'nombre' => 'Wolf Girls',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Wolf_Girls.jpg',    'activo' => true],
            ['id' => 23, 'nombre' => 'Carnaval Rio',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Carnaval_rio.jpg',    'activo' => true],
            ['id' => 24, 'nombre' => 'Toxic Boys',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Toxic_Boys.jpg',    'activo' => true],
            ['id' => 25, 'nombre' => 'White Angels',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/White_Angels.jpg',    'activo' => true],
            ['id' => 26, 'nombre' => 'Disco Boys',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Disco_Boys.jpg',    'activo' => true],
            ['id' => 27, 'nombre' => 'Skulls',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Skulls.jpg',    'activo' => true],
            ['id' => 28, 'nombre' => 'Mad Max',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Mad_Max.jpg',    'activo' => true],
            ['id' => 29, 'nombre' => 'Marshmellows',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Marshmellows.jpg',    'activo' => true],
            ['id' => 30, 'nombre' => 'Robots Rock & Roll','tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Rock_Roll.jpg',    'activo' => true],
            ['id' => 31, 'nombre' => 'Loros',             'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Loros.jpg',    'activo' => true],
            ['id' => 32, 'nombre' => 'Policeman',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Policeman.jpg',    'activo' => true],
            ['id' => 33, 'nombre' => 'Casa de Papel',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Casa_Papel.jpg',    'activo' => true],
            ['id' => 34, 'nombre' => 'Peluches',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Peluches.jpg',    'activo' => true],
            ['id' => 35, 'nombre' => 'Androides Girls',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Androides_Girls.jpg',    'activo' => true],
            ['id' => 36, 'nombre' => 'V de Vendetta',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/V_Vendetta.jpg',    'activo' => true],
            ['id' => 37, 'nombre' => 'Chimpances',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Chumpances.jpg',    'activo' => true],
            ['id' => 38, 'nombre' => 'Robots LMFAO',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_LMFAO.jpg',    'activo' => true],
            ['id' => 39, 'nombre' => 'Robots Bomberos',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Bomberos.jpg',    'activo' => true],
            ['id' => 40, 'nombre' => 'Sailor Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Sailor_Girls.jpg',    'activo' => true],
            ['id' => 41, 'nombre' => 'Aliens Platinum',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Aliens_Platinum.jpg',    'activo' => true],
            ['id' => 42, 'nombre' => 'Aliens Saturno',    'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Aliens_Saturno.jpg',    'activo' => true],
            ['id' => 43, 'nombre' => 'Zebras Boys',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Zebras_Boys.jpg',    'activo' => true],
            ['id' => 44, 'nombre' => 'Zebras Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Zebras_Girls.jpg',    'activo' => true],
            ['id' => 45, 'nombre' => 'Pandas 3D',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Pandas_3D.jpg',    'activo' => true],
            ['id' => 46, 'nombre' => 'Lady Mirror\'s',    'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Lady_Mirror.jpg',    'activo' => true],
            ['id' => 47, 'nombre' => 'Panda Chinatown',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Panda_Chinatown.jpg',    'activo' => true],
            ['id' => 48, 'nombre' => 'Los Pepes',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Los_Pepes.jpg',    'activo' => true],
            ['id' => 49, 'nombre' => 'Patrulla Canina',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Patrulla_Canina.jpg',    'activo' => true],
            ['id' => 50, 'nombre' => 'Robots Golden V2',  'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Golden_V2.jpg',    'activo' => true],
            ['id' => 51, 'nombre' => 'Star Wars',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Star_Wars.jpg',    'activo' => true],
            ['id' => 52, 'nombre' => 'Scream',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Scream.jpg',    'activo' => true],
            ['id' => 53, 'nombre' => 'Mexican Skull',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Mexican_Skull.jpg',    'activo' => true],
            ['id' => 54, 'nombre' => 'Halloween Clown',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Halloween_Clown.jpg',    'activo' => true],
            ['id' => 55, 'nombre' => 'La muerte',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/La_Muerte.jpg',    'activo' => true],
            ['id' => 56, 'nombre' => 'Robots Full LED',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Full_LED.jpg',    'activo' => true],
            ['id' => 57, 'nombre' => 'Halloween Pumpkin', 'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Halloween_Pumpkin.jpg',    'activo' => true],
            ['id' => 58, 'nombre' => 'Demonios',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Demonios.jpg',    'activo' => true],

            ['id' => 59, 'nombre' => 'Barra Limbo',          'tipo' => 'accesorio', 'precio' => 50, 'imagen' => 'images/Barra_Limbo.jpg',    'activo' => true],
            ['id' => 60, 'nombre' => 'Pistola de Burbujas',          'tipo' => 'accesorio', 'precio' => 50, 'imagen' => 'images/Pistola_Burbujas.jpg',    'activo' => true],
            ['id' => 61, 'nombre' => 'Bengalas LED',          'tipo' => 'accesorio', 'precio' => 50, 'imagen' => 'images/Bengalas_LED.jpg',    'activo' => true],

            
            ['id' => 62, 'nombre' => 'Pack Bronce',          'tipo' => 'pack', 'precio' => 300, 'imagen' => 'images/Pack_Bronce.jpg',    'activo' => true],
            ['id' => 63, 'nombre' => 'Pack Silver',          'tipo' => 'pack', 'precio' => 450, 'imagen' => 'images/Pack_Silver.jpg',    'activo' => true],
            ['id' => 64, 'nombre' => 'Pack Gold',          'tipo' => 'pack', 'precio' => 600, 'imagen' => 'images/Pack_Gold.jpg',    'activo' => true],
            ['id' => 65, 'nombre' => 'Pack Platinum',          'tipo' => 'pack', 'precio' => 800, 'imagen' => 'images/Pack_Platinum.jpg',    'activo' => true],
        ]);
    }
}
