<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the whole database.
     */
    public function run(): void
    {


        User::factory(5)->create();


        $this->call([
            EventSeeder::class,
            SeatSeeder::class,
            TicketSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Super admin',
            'email' => 'q@q.hu',
            'admin' => true,
            'password' => Hash::make('q'),
        ]);

        User::factory()->create([
            'name' => 'Super User',
            'email' => 'qq@q.hu',
            'admin' => false,
            'password' => Hash::make('qq'),
        ]);
    }
}
