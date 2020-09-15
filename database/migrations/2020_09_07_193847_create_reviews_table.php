<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reviews', function (Blueprint $table) {
            $table->id();
            $table->text('Review_description');
            $table->tinyInteger('rating');	
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');///relation with the user table
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');///relation with the school table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Reviews');
    }
}