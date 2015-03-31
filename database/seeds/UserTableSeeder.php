<?php

use App\Src\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Mubarak',
            'email' => 'mubarakesmail3@gmail.com',
            'password' => Hash::make('123'),
            'active' => 1
        ]);
    }
}