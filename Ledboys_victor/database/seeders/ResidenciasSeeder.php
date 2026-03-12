<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('residencias')->insert([
            [
                'cliente_id' => 1,
                'fecha_inicio' => '2026-06-01',
                'fecha_fin' => '2026-08-31',
                'dia_semana' => 5, // viernes
                'precio' => 250,
                'estado' => 'activa',
                'stripe_subscription_id' => 'sub_eclipse_verano',
            ],
        ]);
    }
}
