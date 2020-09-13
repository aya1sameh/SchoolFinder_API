<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_stages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('stage',['nursery','KG','Primary','Secondary']);
            $table->unsignedBigInteger('school_id');

            
            //$table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');//providing an error "Foreign key constraint is incorrectly formed"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_stages');
    }
}
