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
            $table->enum('school_stages',['nursery','KG','Primary','Secondary']);
            $table->enum('school_certificate',['National','IGCSE','SAT','IB']);
            $table->enum('gender',['Mix','Girls Only','Boys Only']);
            $table->string('language');
            $table->string('address');
            $table->bigInteger('phone_number');
            $table->bigInteger('fees');
            $table->text('description');
            $table->boolean('is_approved');
            $table->year('establishing_year');
            //admin
            //facilities,urls,images
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
