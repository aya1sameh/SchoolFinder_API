<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_likes', function (Blueprint $table) {
            $table->unsignedBigInteger ('user_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
            //$table->foreign('post ID')->references('id')->on('Community_posts')->onDelete('cascade'); ///relation with the community posts table
            $table->timestamps();
            $table ->boolean('liked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_likes');
    }
}
