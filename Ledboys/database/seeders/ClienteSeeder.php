<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->insert([
            [
                'nombre' => 'Discoteca Eclipse',
                'email' => 'contacto@eclipse.com',
                'telefono' => '600111222',
                'stripe_customer_id' => 'cus_eclipse',
            ],
            [
                'nombre' => 'Laura Martínez',
                'email' => 'laura@gmail.com',
                'telefono' => '611222333',
                'stripe_customer_id' => 'cus_laura',
            ],
            [
                'nombre' => 'Eventos Premium SL',
                'email' => 'info@eventospremium.com',
                'telefono' => '622333444',
                'stripe_customer_id' => 'cus_premium',
            ],
        ]);
    }
}
