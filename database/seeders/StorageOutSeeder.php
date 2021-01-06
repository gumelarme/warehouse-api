<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageOut;
class StorageOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $out_storage = [
            [
                "id" => 1,
                "storage_id" => 1,
                "user_id" => 1,
                "description" => null,
                "quantity" => 10
            ],
            [
                "id" => 2,
                "storage_id" => 2,
                "user_id" => 1,
                "description" => "补充发",
                "quantity" => 30
            ],
        ];

        foreach($out_storage as  $item){
            StorageOut::create($item);
        }
    }
}
