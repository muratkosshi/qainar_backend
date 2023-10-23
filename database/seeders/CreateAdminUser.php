<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
           'firstname'=>'admin',
           'lastname' => 'admin',
           'phone' => '87774334949',
           'email' => 'murat.kosshi@gmail.com',
           'password' => bcrypt('admin'),
            'status' => 1
        ]);
    }
}
