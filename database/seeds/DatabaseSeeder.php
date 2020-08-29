<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@test.com',
            'password'  => Hash::make('admin'),
            'role'      => 'admin'
        ]);

        factory(User::class, 5)->create()->each(function ($user) {
            factory(Todo::class, 10)->create(['user_id' => $user->id]);
        });
    }
}
