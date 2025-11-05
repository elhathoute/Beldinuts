<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@beldinuts.ma',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'phone' => '+212615919437',
            'address' => 'Dar bouaza nouacer, Casablanca, Maroc',
        ]);

        // Create client users
        \App\Models\User::factory(10)->create([
            'role' => 'client',
        ]);
    }
}
