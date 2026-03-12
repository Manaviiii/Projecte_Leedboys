<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTrajesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_trajes')->insert([
            [
                'item_id' => 1,
                'tipo_traje' => 'zancos',
                'genero' => 'unisex',
                'stock_total' => 6,
            ],
            [
                'item_id' => 2,
                'tipo_traje' => 'zancos',
                'genero' => 'unisex',
                'stock_total' => 4,
            ],
            [
                'item_id' => 3,
                'tipo_traje' => 'sin_zancos',
                'genero' => 'unisex',
                'stock_total' => 2,
            ],
        ]);
    }
}
