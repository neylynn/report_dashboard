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
            'email' => 'finance@blueoceanmm.com',
            'password' => Hash::make('F!n@nce2023'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'BP Demo',
            'email' => 'bpdemo@bp.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2
        ]);
    }
}
