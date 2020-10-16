<?php

use App\Http\Controllers\Posts\LikesOfPosts;
use App\Models\CommentOnPost;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AdsSeeder::class);
        $this->call(schoolSeeder::class);
        $this->call(CommunityPostSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(LikesOfReviewsSeeder::class);
        $this->call(DislikesOfReviewsSeeder::class);
        $this->call(CommentsOnPostsSeeder::class);
        $this->call(LikesOnPostsSeeder::class);
      
    }
}
