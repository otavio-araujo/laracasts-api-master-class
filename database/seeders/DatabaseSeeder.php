<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $users = User::factory(10)->create();

         Ticket::factory(100)
             ->recycle($users)
             ->create();

         User::create([
            'email' => 'manager@manager.com',
             'password' => bcrypt('password'),
             'name' => 'Manager',
             'is_manager' => true
         ]);

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
