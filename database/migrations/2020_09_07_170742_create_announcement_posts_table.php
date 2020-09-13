<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Announcement_Posts',function(Blueprint $table){
            $table->increments('AnnounPost_id');
            $table->foreign('User_id')->references('id')->on('users')->onDelete('cascade'); ///relation with the user table
            $table->string('AnnounPost_Content');
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
        Schema::dropIfExists('Announcement_Posts');
    }
}
