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
            ['id' => 1,  'nombre' => 'Ironman',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Ironman con zancos.', 'imagen' => 'images/Ironman.jpg',          'activo' => true],
            ['id' => 2,  'nombre' => 'Circus',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Circus con zancos.', 'imagen' => 'images/Circus.jpg',           'activo' => true],
            ['id' => 3,  'nombre' => 'Wonderland',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Wonderland sin zancos.', 'imagen' => 'images/Wonderland.jpg',       'activo' => true],
            ['id' => 4,  'nombre' => 'Golden Angels',     'tipo' => 'traje', 'precio' => 300, 'descripcion' => 'Traje LED Golden Angels con zancos.', 'imagen' => 'images/Golden_Angels.jpg',    'activo' => true],
            ['id' => 5,  'nombre' => 'Daft Punk',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Daft Punk con zancos.', 'imagen' => 'images/daft_punk.jpg',        'activo' => true],
            ['id' => 6,  'nombre' => 'Mariachis',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Mariachis con zancos.', 'imagen' => 'images/mariachis.jpg',        'activo' => true],
            ['id' => 7,  'nombre' => 'The Mask',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED The Mask con zancos.', 'imagen' => 'images/The_Mask.jpg',         'activo' => true],
            ['id' => 8,  'nombre' => 'The Joker',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED The Joker con zancos.', 'imagen' => 'images/The_Joker.jpg',        'activo' => true],
            ['id' => 9,  'nombre' => 'Steampunk',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Steampunk con zancos.', 'imagen' => 'images/Steampunk.jpg',        'activo' => true],
            ['id' => 10, 'nombre' => 'El Grinch',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED El Grinch con zancos.', 'imagen' => 'images/El_Grinch.jpg',        'activo' => true],
            ['id' => 11, 'nombre' => 'Neonik Boys',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Neonik Boys sin zancos.', 'imagen' => 'images/Neonik_Boys.jpg',      'activo' => true],
            ['id' => 12, 'nombre' => 'Space Girls',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Space Girls con zancos.', 'imagen' => 'images/Space_Girls.jpg',      'activo' => true],
            ['id' => 13, 'nombre' => 'Wolf Girls',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Wolf Girls con zancos.', 'imagen' => 'images/Wolf_Girls.jpg',       'activo' => true],
            ['id' => 14, 'nombre' => 'Iluminati',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Iluminati con zancos.', 'imagen' => 'images/iluminati.jpg',        'activo' => true],
            ['id' => 15, 'nombre' => 'Árboles',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Árboles con zancos.', 'imagen' => 'images/arboles.jpg',          'activo' => true],
            ['id' => 16, 'nombre' => 'Motomamis',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Motomamis con zancos.', 'imagen' => 'images/motomamis.jpg',        'activo' => true],
            ['id' => 17, 'nombre' => 'Carnaval Rio',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Carnaval Rio con zancos.', 'imagen' => 'images/Carnaval_rio.jpg',     'activo' => true],
            ['id' => 18, 'nombre' => 'Anubis',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Anubis con zancos.', 'imagen' => 'images/anubis.jpg',           'activo' => true],
            ['id' => 19, 'nombre' => 'Toxic Boys',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Toxic Boys con zancos.', 'imagen' => 'images/Toxic_Boys.jpg',       'activo' => true],
            ['id' => 20, 'nombre' => 'White Angels',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED White Angels con zancos.', 'imagen' => 'images/White_Angels.jpg',     'activo' => true],
            ['id' => 21, 'nombre' => 'Disco Boys',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Disco Boys con zancos.', 'imagen' => 'images/Disco_Boys.jpg',       'activo' => true],
            ['id' => 22, 'nombre' => 'Disco Girls',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Disco Girls con zancos.', 'imagen' => 'images/disco_girls.jpg',      'activo' => true],
            ['id' => 23, 'nombre' => 'Skulls',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Skulls con zancos.', 'imagen' => 'images/Skulls.jpg',           'activo' => true],
            ['id' => 24, 'nombre' => 'Mad Max',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Mad Max con zancos.', 'imagen' => 'images/Mad_Max.jpg',          'activo' => true],
            ['id' => 25, 'nombre' => 'Marshmellows',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Marshmellows con zancos.', 'imagen' => 'images/Marshmellows.jpg',     'activo' => true],
            ['id' => 26, 'nombre' => 'Robots Rock & Roll','tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Robots Rock & Roll con zancos.', 'imagen' => 'images/Rock_Roll.jpg',        'activo' => true],
            ['id' => 27, 'nombre' => 'Loros',             'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Loros con zancos.', 'imagen' => 'images/Loros.jpg',            'activo' => true],
            ['id' => 28, 'nombre' => 'Policeman',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Policeman con zancos.', 'imagen' => 'images/Policeman.jpg',        'activo' => true],
            ['id' => 29, 'nombre' => 'Casa de Papel',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Casa de Papel con zancos.', 'imagen' => 'images/Casa_Papel.jpg',       'activo' => true],
            ['id' => 30, 'nombre' => 'Peluches',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Peluches sin zancos.', 'imagen' => 'images/Peluches.jpg',         'activo' => true],
            ['id' => 31, 'nombre' => 'Androides Girls',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Androides Girls con zancos.', 'imagen' => 'images/Androides_Girls.jpg',  'activo' => true],
            ['id' => 32, 'nombre' => 'V de Vendetta',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED V de Vendetta con zancos.', 'imagen' => 'images/V_Vendetta.jpg',       'activo' => true],
            ['id' => 33, 'nombre' => 'Chimpances',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Chimpances con zancos.', 'imagen' => 'images/Chumpances.jpg',       'activo' => true],
            ['id' => 34, 'nombre' => 'Robots LMFAO',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Robots LMFAO sin zancos.', 'imagen' => 'images/Robots_LMFAO.jpg',     'activo' => true],
            ['id' => 35, 'nombre' => 'Robots Bomberos',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Robots Bomberos con zancos.', 'imagen' => 'images/Robots_Bomberos.jpg',  'activo' => true],
            ['id' => 36, 'nombre' => 'Sailor Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Sailor Girls con zancos.', 'imagen' => 'images/Sailor_Girls.jpg',     'activo' => true],
            ['id' => 37, 'nombre' => 'Aliens Platinum',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Aliens Platinum con zancos.', 'imagen' => 'images/Aliens_Platinum.jpg',  'activo' => true],
            ['id' => 38, 'nombre' => 'Aliens Saturno',    'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Aliens Saturno con zancos.', 'imagen' => 'images/Aliens_Saturno.jpg',   'activo' => true],
            ['id' => 39, 'nombre' => 'Zebras Boys',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Zebras Boys con zancos.', 'imagen' => 'images/Zebras_Boys.jpg',      'activo' => true],
            ['id' => 40, 'nombre' => 'Zebras Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Zebras Girls con zancos.', 'imagen' => 'images/Zebras_Girls.jpg',     'activo' => true],
            ['id' => 41, 'nombre' => 'Medusa',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Medusa con zancos.', 'imagen' => 'images/Medusa.jpg',           'activo' => true],
            ['id' => 42, 'nombre' => 'Pandas 3D',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Pandas 3D con zancos.', 'imagen' => 'images/Pandas_3D.jpg',        'activo' => true],
            ['id' => 43, 'nombre' => 'Gladiadores',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Gladiadores con zancos.', 'imagen' => 'images/gladiadores.jpg',      'activo' => true],
            ['id' => 44, 'nombre' => 'Lady Mirror\'s',    'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Lady Mirror\'s con zancos.', 'imagen' => 'images/Lady_Mirror.jpg',      'activo' => true],
            ['id' => 45, 'nombre' => 'Panda Chinatown',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Panda Chinatown sin zancos.', 'imagen' => 'images/Panda_Chinatown.jpg',  'activo' => true],
            ['id' => 46, 'nombre' => 'Los Pepes',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Los Pepes con zancos.', 'imagen' => 'images/Los_Pepes.jpg',        'activo' => true],
            ['id' => 47, 'nombre' => 'Patrulla Canina',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Patrulla Canina con zancos.', 'imagen' => 'images/Patrulla_Canina.jpg',  'activo' => true],
            ['id' => 48, 'nombre' => 'Robots Golden V2',  'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Robots Golden V2 con zancos.', 'imagen' => 'images/Robots_Golden_V2.jpg', 'activo' => true],
            ['id' => 49, 'nombre' => 'Star Wars',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Star Wars con zancos.', 'imagen' => 'images/Star_Wars.jpg',        'activo' => true],
            ['id' => 50, 'nombre' => 'Scream',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Scream con zancos.', 'imagen' => 'images/Scream.jpg',           'activo' => true],
            ['id' => 51, 'nombre' => 'Mexican Skull',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Mexican Skull con zancos.', 'imagen' => 'images/Mexican_Skull.jpg',    'activo' => true],
            ['id' => 52, 'nombre' => 'Halloween Clown',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Halloween Clown con zancos.', 'imagen' => 'images/Halloween_Clown.jpg',  'activo' => true],
            ['id' => 53, 'nombre' => 'La muerte',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED La Muerte con zancos.', 'imagen' => 'images/La_Muerte.jpg',        'activo' => true],
            ['id' => 54, 'nombre' => 'Robots Full LED',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Robots Full LED con zancos.', 'imagen' => 'images/Robots_Full_LED.jpg',  'activo' => true],
            ['id' => 55, 'nombre' => 'Halloween Pumpkin', 'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Halloween Pumpkin con zancos.', 'imagen' => 'images/Halloween_Pumpkin.jpg','activo' => true],
            ['id' => 56, 'nombre' => 'Demonios',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Demonios con zancos.', 'imagen' => 'images/Demonios.jpg',         'activo' => true],
            ['id' => 57, 'nombre' => 'Future Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Future Girls con zancos.', 'imagen' => 'images/future_girls.jpg',     'activo' => true],
            ['id' => 58, 'nombre' => 'Ángeles',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Traje LED Ángeles con zancos.', 'imagen' => 'images/angeles.jpg',          'activo' => true],

            // ACCESORIOS
            ['id' => 59, 'nombre' => 'Barra Limbo',         'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Barra de limbo con iluminación LED. Ideal para animar cualquier evento.', 'imagen' => 'images/Barra_Limbo.jpg',      'activo' => true],
            ['id' => 60, 'nombre' => 'Pistola de Burbujas', 'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Pistola de burbujas LED para crear un ambiente mágico en tu evento.', 'imagen' => 'images/Pistola_Burbujas.jpg', 'activo' => true],
            ['id' => 61, 'nombre' => 'Bengalas LED',        'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Bengalas LED de colores. Seguras y espectaculares para bodas y eventos.', 'imagen' => 'images/Bengalas_LED.jpg',     'activo' => true],

            // PACKS
            ['id' => 62, 'nombre' => 'Hora Loca Bronce',   'tipo' => 'pack', 'precio' => 300, 'descripcion' => 'La Hora Loca más especial para tu evento. Incluye gafas LED, collares LED, pelucas LED y barra de limbo para animar a todos tus invitados.', 'imagen' => null, 'activo' => true],
            ['id' => 63, 'nombre' => 'Hora Loca Plata',    'tipo' => 'pack', 'precio' => 450, 'descripcion' => 'Lleva tu Hora Loca al siguiente nivel. Incluye gafas LED, collares LED, pelucas LED, barra de limbo, accesorios novio y novia, y capa LED para los protagonistas de la noche.', 'imagen' => null, 'activo' => true],
            ['id' => 64, 'nombre' => 'Hora Loca Gold',     'tipo' => 'pack', 'precio' => 600, 'descripcion' => 'La experiencia completa de Hora Loca. Incluye gafas LED, collares LED, pelucas LED, barra de limbo, accesorios novio y novia, capa LED, cámaras personalizadas y gafas temáticas para toda la mesa.', 'imagen' => null, 'activo' => true],
            ['id' => 65, 'nombre' => 'Hora Loca Platinum', 'tipo' => 'pack', 'precio' => 800, 'descripcion' => 'El pack más exclusivo de Hora Loca. Incluye gafas LED, collares LED, pelucas LED, barra de limbo, accesorios novio y novia, capa LED, cámaras personalizadas, gafas temáticas, sombreros LED y una selección de accesorios premium para hacer de tu evento algo único e irrepetible.', 'imagen' => null, 'activo' => true],
        ]);
    }
}