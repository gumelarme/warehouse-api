<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            [
                "id" => 1,
                "name" => "南京A",
                "address" => "南京市鼓楼区"
            ],
            [
                "id" => 2,
                "name" => "南京B",
                "address" => "南京市玄武区"
            ],
            [
                "id" => 3,
                "name" => "南京C",
                "address" => "南京市玄武区"
            ],
        ];

        foreach($warehouses as  $item){
            Warehouse::create($item);
        }
    }
}
