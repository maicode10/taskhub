<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // the shop owner, he is the only admin
        User::create([
            'name'     => 'Maerica Danelyne',
            'email'    => 'admin@taskhub.test',
            'password' => 'Admin@123',
            'role'     => 'admin',
        ]);

        // the 4 staff of the print shop
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