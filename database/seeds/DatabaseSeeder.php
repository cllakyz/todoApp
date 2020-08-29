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
        $admin = User::create([
            'name'      => 'Admin',
            'email'     => 'admin@test.com',
            'password'  => Hash::make('admin'),
            'role'      => 'admin'
        ]);
        /*$user = User::create([
            'name'      => 'User',
            'email'     => 'user@test.com',
            'password'  => Hash::make('secret'),
            'role'      => 'user'
        ]);

        factory(Todo::class, 5)->create(['user_id' => $admin->id]);
        factory(Todo::class, 5)->create(['user_id' => $user->id]);*/

        factory(User::class, 5)->create()->each(function ($user) {
            factory(Todo::class, 10)->create(['user_id' => $user->id]);
        });
    }
}
