<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Shop Owner',
            'email'    => 'admin@taskhub.test',
            'password' => 'Admin@123',
            'role'     => 'admin',
        ]);

        $staff = [
            ['Mai',  'mai@taskhub.test'],
            ['Juve', 'juve@taskhub.test'],
            ['Eric', 'eric@taskhub.test'],
            ['Dani', 'dani@taskhub.test'],
        ];

        foreach ($staff as [$name, $email]) {
            User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => 'Staff@123',
                'role'     => 'staff',
            ]);
        }
    }
}