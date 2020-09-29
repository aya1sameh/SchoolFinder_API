<?php

use Illuminate\Database\Seeder;

class CommentsOnPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   factory(App\Models\CommentOnPost::class, 50)->create();
    }
}
