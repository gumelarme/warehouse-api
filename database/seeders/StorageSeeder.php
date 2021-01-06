<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $storages = [
            [
                "id" => 1,
                "warehouse_id" => 1,
                "goods_id" => 1,
                "quantity" => 100
            ],
            [
                "id" => 2,
                "warehouse_id" => 1,
                "goods_id" => 2,
                "quantity" => 500
            ],
            [
                "id" => 3,
                "warehouse_id" => 2,
                "goods_id" => 3,
                "quantity" => 33
            ],
            [
                "id" => 4,
                "warehouse_id" => 1,
                "goods_id" => 4,
                "quantity" => 198
            ],
        ];

        foreach($storages as  $item){
            Storage::create($item);
        } 
    }
}
