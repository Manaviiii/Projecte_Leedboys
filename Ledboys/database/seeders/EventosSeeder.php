<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosSeeder extends Seeder
{
    public function run()
    {
        DB::table('eventos')->insert([
            [
                'id'                       => 1,
                'cliente_id'               => 2,
                'fecha'                    => '2026-08-14',
                'hora'                     => '22:00',
                'ubicacion'                => 'Sala Razzmatazz, Barcelona',
                'total_precio'             => 150,
                'estado'                   => 'pagado',
                'stripe_payment_intent_id' => 'pi_evento_laura',
            ],
            [
                'id'                       => 2,
                'cliente_id'               => 3,
                'fecha'                    => '2026-05-29',
                'hora'                     => '23:30',
                'ubicacion'                => 'Masia Can Torrents, Igualada',
                'total_precio'             => 200,
                'estado'                   => 'pagado',
                'stripe_payment_intent_id' => 'pi_evento_traje_accesorio',
            ],
            [
                'id'                       => 3,
                'cliente_id'               => 1,
                'fecha'                    => '2026-06-20',
                'hora'                     => '21:00',
                'ubicacion'                => 'Hotel Arts, Barcelona',
                'total_precio'             => 300,
                'estado'                   => 'pagado',
                'stripe_payment_intent_id' => 'pi_pack_led',
            ],
        ]);
    }
}