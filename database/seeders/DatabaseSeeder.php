<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(GoodsSeeder::class);
        $this->call(StorageSeeder::class);
        $this->call(StorageInSeeder::class);
        $this->call(StorageOutSeeder::class);
    }
}
