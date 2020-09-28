<?php

use Illuminate\Database\Seeder;
use PHPUnit\Framework\TestCase;

class PostSeeder extends Seeder
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
