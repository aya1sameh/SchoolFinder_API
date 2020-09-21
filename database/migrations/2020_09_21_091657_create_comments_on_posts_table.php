<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentOnPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('comments_on_posts',function(Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
           //$table->foreign('post_id')->references('id')->on('Community_post')->onDelete('cascade'); ///relation with the community posts table
            $table-> text ('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_on_posts');
    }
}
