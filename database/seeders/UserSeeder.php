<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('1234')
            ],

            [
                'name' => 'employee',
                'email' => 'employee@gmail.com',
                'role' => 'employee',
                'password' => Hash::make('123456')
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
