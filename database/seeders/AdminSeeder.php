<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'username' => 'es_zyg',
            'password' => Hash::make('Purwokerto5'),
            'name' => 'Administrator',
            'email' => 'admin@viera.com',
        ]);
    }
}
