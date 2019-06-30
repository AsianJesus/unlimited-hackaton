<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    const DEFAULT_USER = [
        'name' => 'Elvin',
        'surname' => 'Bayramov',
        'email' => 'email@email.com',
        'password' => 'pass'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 20)->create();

        \App\User::create(self::DEFAULT_USER);
    }
}
