<?php

use Illuminate\Database\Seeder;

class LikesOnPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         factory(App\Models\LikeOnPost::class, 50)->create();
    }
}
