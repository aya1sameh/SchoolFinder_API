<?php

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
        factory(\App\Models\User::class,10)->create();
        $this->call(schoolSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(LikesOfReviewsSeeder::class);
        $this->call(DislikesOfReviews::class);
      
    }
}
