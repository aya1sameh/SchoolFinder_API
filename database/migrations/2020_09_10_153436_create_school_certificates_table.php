<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_certificates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('certificate',['National','IGCSE','SAT','IB']);
            $table->unsignedBigInteger('school_id');


            $table->primary(['school_id','certificate']);
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            
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
        Schema::dropIfExists('school_certificates');
    }
}
