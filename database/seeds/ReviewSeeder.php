<?php

use Illuminate\Database\Seeder;
use PHPUnit\Framework\TestCase;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Review::class, 50)->create();
    }
}
