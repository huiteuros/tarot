<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Alice Martin', 'email' => 'alice@tarot.test', 'elo' => 1200],
            ['name' => 'Bob Dupont', 'email' => 'bob@tarot.test', 'elo' => 1200],
            ['name' => 'Charlie Bernard', 'email' => 'charlie@tarot.test', 'elo' => 1200],
            ['name' => 'David Dubois', 'email' => 'david@tarot.test', 'elo' => 1200],
            ['name' => 'Emma Lambert', 'email' => 'emma@tarot.test', 'elo' => 1200],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'elo' => $userData['elo'],
                'games_played' => 0,
            ]);
        }
    }
}
