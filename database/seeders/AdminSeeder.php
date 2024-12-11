<?php

namespace Database\Seeders;

use App\Enums\Departments;
use App\Models\User;
use App\enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@acerta-intranet.com'],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'department' => Departments::IT->value,
                'role' => Role::Admin->value,
                'password' => Hash::make('Qwerty1234!'),
            ]
        );
    }
}
