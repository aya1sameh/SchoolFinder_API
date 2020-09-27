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
            $table->smallInteger('rating')->default(1);
            $table->unsignedBigInteger('rated_by')->default((0));
            $table->json('community_posts')->nullable();
            $table->json('reviews')->nullable();
            $table->json('external_urls')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

           $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
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
