<?php

use Illuminate\Database\Seeder;

class DislikesOfReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\DislikesOfReview::class, 50)->create();
    }
}
