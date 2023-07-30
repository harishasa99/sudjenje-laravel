<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kreiranje admina
        User::create([
            'name' => 'Admin',
            'email' => 'harishasa99@gmail.com',
            'password' => bcrypt('admin123'), // Promenite 'password' sa željenom lozinkom
            'type' => 'admin',
            'active' => 1,
            'approved' => 1,
        ]);
    }
}
