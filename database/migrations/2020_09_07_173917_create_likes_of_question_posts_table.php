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
            $table->integer('User_id'); ///relation with the user table
            $table->integer('QuestionPost_id'); ///relation with the AnnounPost table
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
