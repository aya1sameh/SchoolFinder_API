<?php

use Illuminate\Database\Seeder;

class LikesOfReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         factory(App\Models\LikesOfReview::class, 50)->create();
    }
}
