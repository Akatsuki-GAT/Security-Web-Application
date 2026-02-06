<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::create([
        'username'  => 'admin',
        'email'     => 'admin@lostfound.com',
        'full_name' => 'System Administrator',
        'contact'   => null,
        'password'  => Hash::make('StrongAdminPassword123'),
        'role'      => 'admin',
    ]);
    }
}
