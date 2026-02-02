<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eventos')->insert([
            [
                'id' => 1,
                'cliente_id' => 2, // Laura
                'fecha' => '2026-02-14',
                'total_precio' => 150,
                'estado' => 'pagado',
                'stripe_payment_intent_id' => 'pi_evento_laura',
            ],
            [
                'id' => 2,
                'cliente_id' => 3, // Eventos Premium
                'fecha' => '2026-03-01',
                'total_precio' => 200,
                'estado' => 'pagado',
                'stripe_payment_intent_id' => 'pi_evento_traje_accesorio',
            ],
            [
                'id' => 3,
                'cliente_id' => 1, // Discoteca
                'fecha' => '2026-06-20',
                'total_precio' => 300,
                'estado' => 'reservado',
                'stripe_payment_intent_id' => 'pi_pack_led',
            ],
        ]);
    }
}
