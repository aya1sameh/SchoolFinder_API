<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SchoolCertificate;
use App\Models\SchoolStage;
use App\Models\SchoolImage;

class School extends Model
{
    //Function that returns stages that school has
    public function stages()
    {
        return $this->hasMany(SchoolStage::class);
    }

    //function that return certificates that school afford
    public function certificates()
    {
        return $this->hasMany(SchoolCertificate::class);
    }

    //function that returns gallery of images
    public function images()
    {
        return $this->hasMany(SchoolImage::class);
    }

    //function that retun reviews of school
    public function reviews()
    {

    }
    //function that calculates overall rating of school
    public function calculateOverAllRating()
    {
        //
    }

    //Function that returns number of users who like the school
    public function numberOfLikes()
    {
        //
    }


    //Function that returns number of users who viewed the scool
    public function numberOfViews()
    {
        //
    }

    //function that returns number of users who rated school
    public function numberOfRatings()
    {
        //
    }

    
}
