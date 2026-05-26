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
            ['id' => 1,  'nombre' => 'Ironman',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Convierte tu evento en una misión épica.', 'imagen' => 'images/Ironman.jpg',          'activo' => true],
            ['id' => 2,  'nombre' => 'Circus',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Diviértete con los animales más locos del Circo.', 'imagen' => 'images/Circus.jpg',           'activo' => true],
            ['id' => 3,  'nombre' => 'Wonderland',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Bienvenido al caos, aquí nada es lo que parece.', 'imagen' => 'images/Wonderland.jpg',       'activo' => true],
            ['id' => 4,  'nombre' => 'Golden Angels',     'tipo' => 'traje', 'precio' => 300, 'descripcion' => 'Eleva tu evento con la elegancia dorada.', 'imagen' => 'images/Golden_Angels.jpg',    'activo' => true],
            ['id' => 5,  'nombre' => 'Daft Punk',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Ellos son los superstars del rock electrónico, back to 2000.', 'imagen' => 'images/daft_punk.jpg',        'activo' => true],
            ['id' => 6,  'nombre' => 'Mariachis',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Como dicen en México... El muerto al pozo y el vivo al gozo.', 'imagen' => 'images/mariachis.jpg',        'activo' => true],
            ['id' => 7,  'nombre' => 'The Mask',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Tu evento necesita locura, ritmo y brillo? Solamente pídemelo y llego girando como un tornado .', 'imagen' => 'images/The_Mask.jpg',         'activo' => true],
            ['id' => 8,  'nombre' => 'The Joker',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'No soy un animador… soy la locura que tu fiesta estaba esperando.', 'imagen' => 'images/The_Joker.jpg',        'activo' => true],
            ['id' => 9,  'nombre' => 'Steampunk',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Este guardián del vapor y la luz, es una mezcla de arte con tecnología.', 'imagen' => 'images/Steampunk.jpg',        'activo' => true],
            ['id' => 10, 'nombre' => 'El Grinch',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Este personaje no solamente se roba la Navidad, también se roba el Show.', 'imagen' => 'images/El_Grinch.jpg',        'activo' => true],
            ['id' => 11, 'nombre' => 'Neonik Boys',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Vibra fluorescente: energía pura en la pista, neón en cada latido.', 'imagen' => 'images/Neonik_Boys.jpg',      'activo' => true],
            ['id' => 12, 'nombre' => 'Space Girls',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'No, no es de Ibiza. Esta animación es de otro planeta.', 'imagen' => 'images/Space_Girls.jpg',      'activo' => true],
            ['id' => 13, 'nombre' => 'Wolf Girls',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Estas lobas saben bien como animar tu manada.', 'imagen' => 'images/Wolf_Girls.jpg',       'activo' => true],
            ['id' => 14, 'nombre' => 'Iluminati',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'El ojo que todo lo ve desde las alturas.', 'imagen' => 'images/iluminati.jpg',        'activo' => true],
            ['id' => 15, 'nombre' => 'Árboles',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Tranqui, estos troncos no te van a dejar plantados.', 'imagen' => 'images/arboles.jpg',          'activo' => true],
            ['id' => 16, 'nombre' => 'Motomamis',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Actitud sobre ruedas.... mejor dicho sobre zancos!!. ¿Listos para el viaje?', 'imagen' => 'images/motomamis.jpg',        'activo' => true],
            ['id' => 17, 'nombre' => 'Carnaval Rio',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'No hay que llorar que la vida es un Carnaval y las penas se van bailando.', 'imagen' => 'images/Carnaval_rio.jpg',     'activo' => true],
            ['id' => 18, 'nombre' => 'Anubis',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Con estos guardianes de la muerte te sentirás como un auténtico Faraón.', 'imagen' => 'images/anubis.jpg',           'activo' => true],
            ['id' => 19, 'nombre' => 'Toxic Boys',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Una energía nuclear no puede faltar para el BOOM de tu fiesta!!', 'imagen' => 'images/Toxic_Boys.jpg',       'activo' => true],
            ['id' => 20, 'nombre' => 'White Angels',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'La NOVIA estaría bien escoltada con sus Ángeles de Charlie.', 'imagen' => 'images/White_Angels.jpg',     'activo' => true],
            ['id' => 21, 'nombre' => 'Disco Boys',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'No puede faltar una bola de disco en tu fiesta.', 'imagen' => 'images/Disco_Boys.jpg',       'activo' => true],
            ['id' => 22, 'nombre' => 'Disco Girls',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Estos vestuarios elegantes brillan más que la luna.', 'imagen' => 'images/disco_girls.jpg',      'activo' => true],
            ['id' => 23, 'nombre' => 'Skulls',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'En elegancia y maldad.', 'imagen' => 'images/Skulls.jpg',           'activo' => true],
            ['id' => 24, 'nombre' => 'Mad Max',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Ellas son todo un espectáculo postapocalíptico.', 'imagen' => 'images/Mad_Max.jpg',          'activo' => true],
            ['id' => 25, 'nombre' => 'Marshmellows',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Los más famosos del Catálogo, ha sido un exitazo este 2024.', 'imagen' => 'images/Marshmellows.jpg',     'activo' => true],
            ['id' => 26, 'nombre' => 'Robots Rock & Roll','tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Estos personajes basados en Nirvana marcarán el ritmo de la noche.', 'imagen' => 'images/Rock_Roll.jpg',        'activo' => true],
            ['id' => 27, 'nombre' => 'Loros',             'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Dale un toque Amazónico from Brasil.', 'imagen' => 'images/Loros.jpg',            'activo' => true],
            ['id' => 28, 'nombre' => 'Policeman',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Atención a todas la unidades, necesitáis refuerzos en vuestra fiesta!', 'imagen' => 'images/Policeman.jpg',        'activo' => true],
            ['id' => 29, 'nombre' => 'Casa de Papel',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Igual que roban un banco, se robarán toda tu atención.', 'imagen' => 'images/Casa_Papel.jpg',       'activo' => true],
            ['id' => 30, 'nombre' => 'Peluches',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Estas divertidas mascotas van por "los suelos", te harán beber o bailar como nunca. ', 'imagen' => 'images/Peluches.jpg',         'activo' => true],
            ['id' => 31, 'nombre' => 'Androides Girls',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Estas chicas del futuro, le darán luz a tu fiesta.', 'imagen' => 'images/Androides_Girls.jpg',  'activo' => true],
            ['id' => 32, 'nombre' => 'V de Vendetta',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Misterioso vengador anarquista va a robarse el Show cuando menos te lo esperes.', 'imagen' => 'images/V_Vendetta.jpg',       'activo' => true],
            ['id' => 33, 'nombre' => 'Chimpances',        'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'En este planeta de los simios pongas lo que les pongas les queda bien.', 'imagen' => 'images/Chumpances.jpg',       'activo' => true],
            ['id' => 34, 'nombre' => 'Robots LMFAO',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Party rock is in the house tonight.', 'imagen' => 'images/Robots_LMFAO.jpg',     'activo' => true],
            ['id' => 35, 'nombre' => 'Robots Bomberos',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Cuando el pary está prendio.', 'imagen' => 'images/Robots_Bomberos.jpg',  'activo' => true],
            ['id' => 36, 'nombre' => 'Sailor Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'A estas chicas se les da bien navegar y conquistar corazones.', 'imagen' => 'images/Sailor_Girls.jpg',     'activo' => true],
            ['id' => 37, 'nombre' => 'Aliens Platinum',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Invasión alienígena llega a la Tierra, conquistan cada discoteca que pisan.', 'imagen' => 'images/Aliens_Platinum.jpg',  'activo' => true],
            ['id' => 38, 'nombre' => 'Aliens Saturno',    'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'En la NASA dicen que esta animación es de otro planeta, pruébalos y verás. ', 'imagen' => 'images/Aliens_Saturno.jpg',   'activo' => true],
            ['id' => 39, 'nombre' => 'Zebras Boys',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Como dice una canción... Este pary es un Safari.', 'imagen' => 'images/Zebras_Boys.jpg',      'activo' => true],
            ['id' => 40, 'nombre' => 'Zebras Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Detalles realistas de esta hermosa especie africana.', 'imagen' => 'images/Zebras_Girls.jpg',     'activo' => true],
            ['id' => 41, 'nombre' => 'Medusa',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Vienen del espacio para quedarse, ya que esta temática es espacialmente especial.', 'imagen' => 'images/Medusa.jpg',           'activo' => true],
            ['id' => 42, 'nombre' => 'Pandas 3D',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Los personajes más pedidos del 2023, son la elegancia en blanco y negro.', 'imagen' => 'images/Pandas_3D.jpg',        'activo' => true],
            ['id' => 43, 'nombre' => 'Gladiadores',       'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Nuestros guerreros te animarán cualquier batalla.', 'imagen' => 'images/gladiadores.jpg',      'activo' => true],
            ['id' => 44, 'nombre' => 'Lady Mirror\'s',    'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Espejito, espejito ya sabe quién son las más bellas del reino.', 'imagen' => 'images/Lady_Mirror.jpg',      'activo' => true],
            ['id' => 45, 'nombre' => 'Panda Chinatown',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Recién llegados de la Asia profunda, te harán creer que estás en China.', 'imagen' => 'images/Panda_Chinatown.jpg',  'activo' => true],
            ['id' => 46, 'nombre' => 'Los Pepes',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Diviértete y disfruta con ellos en la pista, son los reyes del bailoteo.', 'imagen' => 'images/Los_Pepes.jpg',        'activo' => true],
            ['id' => 47, 'nombre' => 'Patrulla Canina',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Va a ser una divertida noche, patrullando la ciudad.', 'imagen' => 'images/Patrulla_Canina.jpg',  'activo' => true],
            ['id' => 48, 'nombre' => 'Robots Golden V2',  'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Dale un toque de elegancia, lujo y glamour a tus celebraciones.', 'imagen' => 'images/Robots_Golden_V2.jpg', 'activo' => true],
            ['id' => 49, 'nombre' => 'Star Wars',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Soldados imperiales conquistan fiestas de otra Galaxia.', 'imagen' => 'images/Star_Wars.jpg',        'activo' => true],
            ['id' => 50, 'nombre' => 'Scream',            'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Si suena (Ring Ring), no cojas el teléfono.', 'imagen' => 'images/Scream.jpg',           'activo' => true],
            ['id' => 51, 'nombre' => 'Mexican Skull',     'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'El que por su gusto muere, hasta la muerte le sabe. Ideal para Halloween.', 'imagen' => 'images/Mexican_Skull.jpg',    'activo' => true],
            ['id' => 52, 'nombre' => 'Halloween Clown',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Si te los encuentras de frente no sabrás si reír o llorar.', 'imagen' => 'images/Halloween_Clown.jpg',  'activo' => true],
            ['id' => 53, 'nombre' => 'La muerte',         'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Todos los caminos llevan a La Muerte.', 'imagen' => 'images/La_Muerte.jpg',        'activo' => true],
            ['id' => 54, 'nombre' => 'Robots Full LED',   'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Los robots nunca fallan, son los que más LED llevan y son gigantes y llamativos.', 'imagen' => 'images/Robots_Full_LED.jpg',  'activo' => true],
            ['id' => 55, 'nombre' => 'Halloween Pumpkin', 'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Las calabazas brillan a la luz de la luna.', 'imagen' => 'images/Halloween_Pumpkin.jpg','activo' => true],
            ['id' => 56, 'nombre' => 'Demonios',          'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Bailarás con los demonios que te llevarán al cielo.', 'imagen' => 'images/Demonios.jpg',         'activo' => true],
            ['id' => 57, 'nombre' => 'Future Girls',      'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Elegancia y mucha LUZ para las bodas y white parties.', 'imagen' => 'images/future_girls.jpg',     'activo' => true],
            ['id' => 58, 'nombre' => 'Ángeles',           'tipo' => 'traje', 'precio' => 150, 'descripcion' => 'Cuando los ángeles llegan, los demonios se van.', 'imagen' => 'images/angeles.jpg',          'activo' => true],

            // ACCESORIOS
            ['id' => 59, 'nombre' => 'Barra Limbo',         'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Barra de limbo con iluminación LED. Ideal para animar cualquier evento.', 'imagen' => 'images/Barra_Limbo.jpg',      'activo' => true],
            ['id' => 60, 'nombre' => 'Pistola de Burbujas', 'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Pistola de burbujas LED para crear un ambiente mágico en tu evento.', 'imagen' => 'images/Pistola_Burbujas.jpg', 'activo' => true],
            ['id' => 61, 'nombre' => 'Bengalas LED',        'tipo' => 'accesorio', 'precio' => 50,  'descripcion' => 'Bengalas LED de colores. Seguras y espectaculares para bodas y eventos.', 'imagen' => 'images/Bengalas_LED.jpg',     'activo' => true],

            // PACKS
            ['id' => 62, 'nombre' => 'Hora Loca Bronce',   'tipo' => 'pack', 'precio' => 300, 'descripcion' => 'La Hora Loca más especial para tu evento. Incluye gafas LED, collares LED, pelucas LED y palos LED para animar a todos tus invitados.', 'imagen' => null, 'activo' => true],
            ['id' => 63, 'nombre' => 'Hora Loca Plata',    'tipo' => 'pack', 'precio' => 450, 'descripcion' => 'Lleva tu Hora Loca al siguiente nivel. Incluye gafas LED, collares LED, pelucas LED, barra de limbo, accesorios , y capa LED para los protagonistas de la noche.', 'imagen' => null, 'activo' => true],
            ['id' => 64, 'nombre' => 'Hora Loca Gold',     'tipo' => 'pack', 'precio' => 600, 'descripcion' => 'La experiencia completa de Hora Loca. Incluye 2 animadores extra (a nuestra eleccion) gafas LED, collares LED, pelucas LED, palos LED, accesorios, capa LED, cámaras personalizadas y gafas temáticas para toda la mesa.', 'imagen' => null, 'activo' => true],
            ['id' => 65, 'nombre' => 'Hora Loca Platinum', 'tipo' => 'pack', 'precio' => 800, 'descripcion' => 'El pack más exclusivo de Hora Loca. Incluye 2 zancudos extra (a nuestra eleccion) gafas LED, collares LED, pelucas LED, palos LED, accesorios, capa LED, cámaras personalizadas, gafas temáticas, sombreros LED y una selección de accesorios premium para hacer de tu evento algo único e irrepetible.', 'imagen' => null, 'activo' => true],
        ]);
    }
}