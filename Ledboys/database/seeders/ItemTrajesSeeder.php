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
            // Unisex
            ['item_id' => 1,  'tipo_traje' => 'zancos',     'genero' => 'unisex', 'stock_total' => 4],
            ['item_id' => 2,  'tipo_traje' => 'sin_zancos',  'genero' => 'unisex', 'stock_total' => 4],
            ['item_id' => 3,  'tipo_traje' => 'zancos',     'genero' => 'unisex', 'stock_total' => 4],
            ['item_id' => 4,  'tipo_traje' => 'sin_zancos',  'genero' => 'unisex', 'stock_total' => 4],
            ['item_id' => 6,  'tipo_traje' => 'sin_zancos',  'genero' => 'unisex', 'stock_total' => 2],
            ['item_id' => 7,  'tipo_traje' => 'zancos',     'genero' => 'unisex', 'stock_total' => 3],
            ['item_id' => 8,  'tipo_traje' => 'zancos',     'genero' => 'unisex', 'stock_total' => 3],
            // Chica
            ['item_id' => 5,  'tipo_traje' => 'sin_zancos',  'genero' => 'chica',  'stock_total' => 4],
            ['item_id' => 9,  'tipo_traje' => 'zancos',     'genero' => 'chica',  'stock_total' => 3],
            ['item_id' => 10, 'tipo_traje' => 'zancos',     'genero' => 'chica',  'stock_total' => 3],
            ['item_id' => 11, 'tipo_traje' => 'sin_zancos',  'genero' => 'chica',  'stock_total' => 4],
            ['item_id' => 12, 'tipo_traje' => 'zancos',     'genero' => 'chica',  'stock_total' => 3],
        ]);
    }
}
