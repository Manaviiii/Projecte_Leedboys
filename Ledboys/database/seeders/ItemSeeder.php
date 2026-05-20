<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            // TRAJES
            ['id' => 1,  'nombre' => 'Ironman',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Ironman.jpg',          'activo' => true],
            ['id' => 2,  'nombre' => 'Circus',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Circus.jpg',           'activo' => true],
            ['id' => 3,  'nombre' => 'Wonderland',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Wonderland.jpg',       'activo' => true],
            ['id' => 4,  'nombre' => 'Golden Angels',     'tipo' => 'traje', 'precio' => 300, 'imagen' => 'images/Golden_Angels.jpg',    'activo' => true],
            ['id' => 5,  'nombre' => 'Daft Punk',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/daft_punk.jpg',        'activo' => true],
            ['id' => 6,  'nombre' => 'Mariachis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/mariachis.jpg',        'activo' => true],
            ['id' => 7,  'nombre' => 'The Mask',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/The_Mask.jpg',         'activo' => true],
            ['id' => 8,  'nombre' => 'The Joker',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/The_Joker.jpg',        'activo' => true],
            ['id' => 9,  'nombre' => 'Steampunk',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Steampunk.jpg',        'activo' => true],
            ['id' => 10, 'nombre' => 'El Grinch',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/El_Grinch.jpg',        'activo' => true],
            ['id' => 11, 'nombre' => 'Neonik Boys',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Neonik_Boys.jpg',      'activo' => true],
            ['id' => 12, 'nombre' => 'Space Girls',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Space_Girls.jpg',      'activo' => true],
            ['id' => 13, 'nombre' => 'Wolf Girls',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Wolf_Girls.jpg',       'activo' => true],
            ['id' => 14, 'nombre' => 'Iluminati',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/iluminati.jpg',        'activo' => true],
            ['id' => 15, 'nombre' => 'Árboles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/arboles.jpg',          'activo' => true],
            ['id' => 16, 'nombre' => 'Motomamis',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/motomamis.jpg',        'activo' => true],
            ['id' => 17, 'nombre' => 'Carnaval Rio',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Carnaval_rio.jpg',     'activo' => true],
            ['id' => 18, 'nombre' => 'Anubis',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/anubis.jpg',           'activo' => true],
            ['id' => 19, 'nombre' => 'Toxic Boys',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Toxic_Boys.jpg',       'activo' => true],
            ['id' => 20, 'nombre' => 'White Angels',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/White_Angels.jpg',     'activo' => true],
            ['id' => 21, 'nombre' => 'Disco Boys',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Disco_Boys.jpg',       'activo' => true],
            ['id' => 22, 'nombre' => 'Disco Girls',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/disco_girls.jpg',      'activo' => true],
            ['id' => 23, 'nombre' => 'Skulls',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Skulls.jpg',           'activo' => true],
            ['id' => 24, 'nombre' => 'Mad Max',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Mad_Max.jpg',          'activo' => true],
            ['id' => 25, 'nombre' => 'Marshmellows',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Marshmellows.jpg',     'activo' => true],
            ['id' => 26, 'nombre' => 'Robots Rock & Roll','tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Rock_Roll.jpg',        'activo' => true],
            ['id' => 27, 'nombre' => 'Loros',             'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Loros.jpg',            'activo' => true],
            ['id' => 28, 'nombre' => 'Policeman',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Policeman.jpg',        'activo' => true],
            ['id' => 29, 'nombre' => 'Casa de Papel',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Casa_Papel.jpg',       'activo' => true],
            ['id' => 30, 'nombre' => 'Peluches',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Peluches.jpg',         'activo' => true],
            ['id' => 31, 'nombre' => 'Androides Girls',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Androides_Girls.jpg',  'activo' => true],
            ['id' => 32, 'nombre' => 'V de Vendetta',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/V_Vendetta.jpg',       'activo' => true],
            ['id' => 33, 'nombre' => 'Chimpances',        'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Chumpances.jpg',       'activo' => true],
            ['id' => 34, 'nombre' => 'Robots LMFAO',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_LMFAO.jpg',     'activo' => true],
            ['id' => 35, 'nombre' => 'Robots Bomberos',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Bomberos.jpg',  'activo' => true],
            ['id' => 36, 'nombre' => 'Sailor Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Sailor_Girls.jpg',     'activo' => true],
            ['id' => 37, 'nombre' => 'Aliens Platinum',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Aliens_Platinum.jpg',  'activo' => true],
            ['id' => 38, 'nombre' => 'Aliens Saturno',    'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Aliens_Saturno.jpg',   'activo' => true],
            ['id' => 39, 'nombre' => 'Zebras Boys',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Zebras_Boys.jpg',      'activo' => true],
            ['id' => 40, 'nombre' => 'Zebras Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Zebras_Girls.jpg',     'activo' => true],
            ['id' => 41, 'nombre' => 'Medusa',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Medusa.jpg',           'activo' => true],
            ['id' => 42, 'nombre' => 'Pandas 3D',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Pandas_3D.jpg',        'activo' => true],
            ['id' => 43, 'nombre' => 'Gladiadores',       'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/gladiadores.jpg',      'activo' => true],
            ['id' => 44, 'nombre' => 'Lady Mirror\'s',    'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Lady_Mirror.jpg',      'activo' => true],
            ['id' => 45, 'nombre' => 'Panda Chinatown',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Panda_Chinatown.jpg',  'activo' => true],
            ['id' => 46, 'nombre' => 'Los Pepes',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Los_Pepes.jpg',        'activo' => true],
            ['id' => 47, 'nombre' => 'Patrulla Canina',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Patrulla_Canina.jpg',  'activo' => true],
            ['id' => 48, 'nombre' => 'Robots Golden V2',  'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Golden_V2.jpg', 'activo' => true],
            ['id' => 49, 'nombre' => 'Star Wars',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Star_Wars.jpg',        'activo' => true],
            ['id' => 50, 'nombre' => 'Scream',            'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Scream.jpg',           'activo' => true],
            ['id' => 51, 'nombre' => 'Mexican Skull',     'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Mexican_Skull.jpg',    'activo' => true],
            ['id' => 52, 'nombre' => 'Halloween Clown',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Halloween_Clown.jpg',  'activo' => true],
            ['id' => 53, 'nombre' => 'La muerte',         'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/La_Muerte.jpg',        'activo' => true],
            ['id' => 54, 'nombre' => 'Robots Full LED',   'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Robots_Full_LED.jpg',  'activo' => true],
            ['id' => 55, 'nombre' => 'Halloween Pumpkin', 'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Halloween_Pumpkin.jpg','activo' => true],
            ['id' => 56, 'nombre' => 'Demonios',          'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/Demonios.jpg',         'activo' => true],
            ['id' => 57, 'nombre' => 'Future Girls',      'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/future_girls.jpg',     'activo' => true],
            ['id' => 58, 'nombre' => 'Ángeles',           'tipo' => 'traje', 'precio' => 150, 'imagen' => 'images/angeles.jpg',          'activo' => true],

            // ACCESORIOS
            ['id' => 59, 'nombre' => 'Barra Limbo',         'tipo' => 'accesorio', 'precio' => 50,  'imagen' => 'images/Barra_Limbo.jpg',      'activo' => true],
            ['id' => 60, 'nombre' => 'Pistola de Burbujas', 'tipo' => 'accesorio', 'precio' => 50,  'imagen' => 'images/Pistola_Burbujas.jpg', 'activo' => true],
            ['id' => 61, 'nombre' => 'Bengalas LED',        'tipo' => 'accesorio', 'precio' => 50,  'imagen' => 'images/Bengalas_LED.jpg',     'activo' => true],

            // PACKS
            ['id' => 62, 'nombre' => 'Pack Bronce',   'tipo' => 'pack', 'precio' => 300, 'imagen' => 'images/Pack_Bronce.jpg',   'activo' => true],
            ['id' => 63, 'nombre' => 'Pack Silver',   'tipo' => 'pack', 'precio' => 450, 'imagen' => 'images/Pack_Silver.jpg',   'activo' => true],
            ['id' => 64, 'nombre' => 'Pack Gold',     'tipo' => 'pack', 'precio' => 600, 'imagen' => 'images/Pack_Gold.jpg',     'activo' => true],
            ['id' => 65, 'nombre' => 'Pack Platinum', 'tipo' => 'pack', 'precio' => 800, 'imagen' => 'images/Pack_Platinum.jpg', 'activo' => true],
        ]);
    }
}