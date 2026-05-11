<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemPacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_accesorios')->insert([
            ['item_id' => 59, 'stock_total' => 10], // Barra Limbo
            ['item_id' => 60, 'stock_total' => 10], // Pistola de Burbujas
            ['item_id' => 61, 'stock_total' => 10], // Bengalas LED
        ]);
    }
}
