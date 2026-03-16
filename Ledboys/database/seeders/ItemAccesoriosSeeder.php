<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemAccesoriosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_accesorios')->insert([
            [
                'item_id' => 4,
                'stock_total' => 10,
            ],
            [
                'item_id' => 5,
                'stock_total' => 5,
            ],
        ]);
    }
}
