<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'type' => 'Internal User',
            'fname' => "Admin",
            'lname' => "ApptiMed",
            'status' => 1,
            'role' => "1",
            'email' => 'admin@amed.lk',
            'password' => Hash::make('12345678'),
        ]);
    }
}
