<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeOnPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    
        {
            Schema::create ('like_on_posts',function(Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger ('user_id');
                $table->unsignedBigInteger('post_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
                $table->foreign('post_id')->references('id')->on('Community_posts')->onDelete('cascade'); ///relation with the community posts table
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
        Schema::dropIfExists('like_on_posts');
    }
}
