<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemAccesoriosSeeder extends Seeder
{
    public function run()
    {
        $carpeta = storage_path('app/Accesorios');

        DB::table('item_accesorios')->insert([
            [
                'item_id'     => 59,
                'stock_total' => 10,
                'imagen'      => file_exists($carpeta . '/BarraLimboLed.jpg')
                                    ? file_get_contents($carpeta . '/BarraLimboLed.jpg')
                                    : null,
            ],
            [
                'item_id'     => 60,
                'stock_total' => 10,
                'imagen'      => file_exists($carpeta . '/pistola_burbujas.jpg')
                                    ? file_get_contents($carpeta . '/pistola_burbujas.jpg')
                                    : null,
            ],
            [
                'item_id'     => 61,
                'stock_total' => 10,
                'imagen'      => file_exists($carpeta . '/bengala_led.jpg')
                                    ? file_get_contents($carpeta . '/bengala_led.jpg')
                                    : null,
            ],
        ]);
    }
}