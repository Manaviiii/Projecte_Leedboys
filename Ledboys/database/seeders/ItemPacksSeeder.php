<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemPacksSeeder extends Seeder
{
    public function run()
    {
        DB::table('item_packs')->insert([
            ['item_id' => 62, 'numero_zancudos' => 2],
            ['item_id' => 63, 'numero_zancudos' => 2],
            ['item_id' => 64, 'numero_zancudos' => 2],
            ['item_id' => 65, 'numero_zancudos' => 2],
        ]);
    }
}