<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->enum('gender',['Mix','Girls Only','Boys Only']);
            $table->string('language');
            $table->string('address');
            $table->bigInteger('phone_number');
            $table->bigInteger('fees');
            $table->text('description')->nullabe();
            $table->boolean('is_approved')->default(0);
            $table->year('establishing_year');
<<<<<<< HEAD
            //admin,urls
=======
            //admin
            //facilities,urls
           // $table->json('communityPosts');
           // $table->json('reviews');
>>>>>>> 52f8c4190a7872798cbd1d5f2f027b1d1585689a
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
