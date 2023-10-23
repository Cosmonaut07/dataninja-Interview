<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = User::factory()->verifiedUser()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password' => Hash::make('password'),
         ]);
        $user->createToken('test');
        UserToken::factory()->create([
            'user_id' => $user->id
        ]);
    }
}
