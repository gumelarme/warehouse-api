<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "id" => 1,
                "username" => "octopus",
                "password" => "octopus_password",
                "is_manager" => true
            ],
            [
                "id" => 2,
                "username" => "someguy",
                "password" => "notsosecure",
                "is_manager" => true
            ],
            [
                "id" => 3,
                "username" => "humbleworker",
                "password" => "humblehumble",
                "is_manager" => false
            ],
        ];

        foreach($users as  $u){
            User::make($u);
        }
    }
}
