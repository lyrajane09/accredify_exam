<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;

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
                'name'          =>  'Lyra Jane Bona-og',
                'email'         =>  'lyra@accredify.com',
                'password'      =>  Hash::make('password')
            ],
            [
                'name'          =>  'John Doe',
                'email'         =>  'john@accredify.com',
                'password'      =>  Hash::make('password')
            ],
            [
                'name'          =>  'Bryan',
                'email'         =>  'bryan@accredify.com',
                'password'      =>  Hash::make('password')
            ]
        ]);
    }
}
