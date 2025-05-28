<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'steven@add-digital.co.uk'],
            [
                'name' => 'Steven Hardy',
                'email' => 'admin@example.com',
                'username' => 'steven.hardy',
                'password' => Hash::make('G01Dx6CrnbQ3'), // Change this in production
                'role' => 'admin',
                'plan' => 'pro',
            ]
        );
    }
}
