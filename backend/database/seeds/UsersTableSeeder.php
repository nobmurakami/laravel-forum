<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
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
        $now = Carbon::now();

        if (User::where('id', 1)->doesntExist()) {

            User::create([
                'name' => env('ADMIN_NAME'),
                'email' => env('ADMIN_EMAIL'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => 1,
            ]);

            User::insert([
                [
                    'name' => 'taro',
                    'email' => 'taro@test.com',
                    'password' => Hash::make('password'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'hanako',
                    'email' => 'hanako@test.com',
                    'password' => Hash::make('password'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => '寿限無寿限無五劫のすりきれ海砂利水魚の水行末雲来末風来末食う寝るところに住むところやぶら小路のぶら小',
                    'email' => 'jugemu@test.com',
                    'password' => Hash::make('password'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        factory(User::class, 10)->create();
    }
}
