<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'taro',
                'email' => 'taro@test.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'hanako',
                'email' => 'hanako@test.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => '寿限無寿限無五劫のすりきれ海砂利水魚の水行末雲来末風来末食う寝るところに住むところやぶら小路のぶら小',
                'email' => 'jugemu@test.com',
                'password' => Hash::make('password'),
            ],
        ]);

        factory(User::class, 10)->create();
    }
}
