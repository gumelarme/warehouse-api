<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goods;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goods = [
            [
                "id" => 1,
                "name" => "高钙牛奶",
                "provider_id" => 1
            ],
            [
                "id" => 2,
                "name" => "牛奶片",
                "provider_id" => 1
            ],
            [
                "id" => 3,
                "name" => "24' LED电视",
                "provider_id" => 2
            ],
            [
                "id" => 4,
                "name" => "键盘",
                "provider_id" => 2
            ],
            [
                "id" => 5,
                "name" => "键盘",
                "provider_id" => 3
            ],
        ];

        foreach($goods as  $item){
            Goods::create($item);
        }  
    }
}
