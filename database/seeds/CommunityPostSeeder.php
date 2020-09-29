<?php

use Illuminate\Database\Seeder;

class CommunityPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\CommunityPost::class, 50)->create();
    }
}
