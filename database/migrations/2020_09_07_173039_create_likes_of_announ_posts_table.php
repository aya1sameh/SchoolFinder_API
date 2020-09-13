<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesOfAnnounPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes_of_announ_posts', function (Blueprint $table) {
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
            $table->foreign('AnnounPost_id')->references('AnnounPost_id')->on('Announcement_Posts')->onDelete('cascade'); ///relation with the AnnounPost table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes_of_announ_posts');
    }
}
