<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                "id" => "34111001",
                "name" => "مدرسة بئر السبع الثانوية أ للبنين",
                "password" => "34111001",
                "phone" => "2140132",
                "department_id" => "25"
            ],


            // Add the remaining user data here...
        ];

        foreach ($users as $user) {
            User::create([
                'id' => $user['id'],
                'name' => $user['name'],
                'password' => $user['password'],
                'phone' => $user['phone'],
                'department_id' => $user['department_id'],
            ]);
        }
    }
}
