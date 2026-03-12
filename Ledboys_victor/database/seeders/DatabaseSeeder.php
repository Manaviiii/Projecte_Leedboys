<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClienteSeeder::class,
            ItemSeeder::class,
            ItemTrajesSeeder::class,
            ItemAccesoriosSeeder::class,
            ItemPacksSeeder::class,
            EventosSeeder::class,
            EventoItemsSeeder::class,
            ResidenciasSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
