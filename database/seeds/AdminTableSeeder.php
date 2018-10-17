<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => 'admin@spcf.com',
            'password' => bcrypt('admin1234'),
            'role' => 1,
        ]);
    }
}
