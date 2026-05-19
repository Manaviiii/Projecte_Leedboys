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
        DB::table('item_packs')->insert([
            ['item_id' => 62, 'numero_zancudos' => 2], // Pack Bronce
            ['item_id' => 63, 'numero_zancudos' => 4], // Pack Silver
            ['item_id' => 64, 'numero_zancudos' => 6], // Pack Gold
            ['item_id' => 65, 'numero_zancudos' => 8], // Pack Platinum
        ]);
    }
}
