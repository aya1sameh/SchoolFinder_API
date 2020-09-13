<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesOfQuestionPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes_of_question_posts', function (Blueprint $table) {
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
            $table->foreign('QuestionPost_id')->references('QuestionPost_id')->on('question_posts')->onDelete('cascade');///relation with the AnnounPost table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes_of_question_posts');
    }
}
