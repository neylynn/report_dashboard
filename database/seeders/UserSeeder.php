<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bp.com',
            'password' => Hash::make('12345678'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'BP Demo',
            'email' => 'bpdemo@bp.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Aung Nai Oo',
            'email' => 'aungnai0322@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Coffee Music Coding',
            'email' => 'aungnaioo24@outlook.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2
        ]);
    }
}
