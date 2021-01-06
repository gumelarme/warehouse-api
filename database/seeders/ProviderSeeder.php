<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $provider = [
            [
                "id" => 1,
                "name" => "内蒙古牛奶公司",
                "contact" => "131023912848"
            ],

            [
                "id" => 2,
                "name" => "浙江科技公司",
                "contact" => "151124293111"
            ],

            [
                "id" => 3,
                "name" => "江苏织品公司",
                "contact" => "132124293111"
            ],

            [
                "id" => 4,
                "name" => "Nike",
                "contact" => "132124293111"
            ],
        ];

        foreach($provider as  $item){
            Provider::create($item);
        }
    }
}
