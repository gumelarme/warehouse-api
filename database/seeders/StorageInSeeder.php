<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageIn;

class StorageInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $in_storage = [
            [
                "id" => 1,
                "storage_id" => 1,
                "user_id" => 1,
                "description" => null,
                "quantity" => 100
            ],
            [
                "id" => 2,
                "storage_id" => 2,
                "user_id" => 1,
                "description" => "补充发",
                "quantity" => 40
            ],
        ];

        foreach($in_storage as  $item){
            StorageIn::create($item);
        }
    }
}
