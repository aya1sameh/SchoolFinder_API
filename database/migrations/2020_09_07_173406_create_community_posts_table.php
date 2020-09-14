<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Community_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade'); ///relation with the user table
            $table->text('CommunityPost_Content'); //will be updated later for the fancy stuff
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
        Schema::dropIfExists('Community_posts');
    }
}
