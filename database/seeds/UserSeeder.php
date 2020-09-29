<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class,20)->create(['role'=>'school_finder_client']);
        factory(\App\Models\User::class,5)->create(['role'=>'app_admin']);
    }
}
