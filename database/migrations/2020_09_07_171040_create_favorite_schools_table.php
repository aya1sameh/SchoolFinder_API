<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_schools', function (Blueprint $table) {
            //is just a many:many rel table bet users and schools
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');

           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
           // $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');//providing an error "Foreign key constraint is incorrectly formed"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_schools');
    }
}
