<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Suami (Admin)',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'position' => 'husband',
            ],
            [
                'name'     => 'Istri (Wife)',
                'email'    => 'wife@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'position' => 'wife',
            ],
            [
                'name'     => 'Anak Pertama (Child 1)',
                'email'    => 'child1@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'position' => 'child',
            ],
            [
                'name'     => 'Anak Kedua (Child 2)',
                'email'    => 'child2@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'position' => 'child',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
