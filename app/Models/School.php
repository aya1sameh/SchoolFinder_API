<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SchoolCertificate;
use App\Models\SchoolStage;
use App\Models\SchoolImage;
use App\Models\SchoolFacility;

class School extends Model
{

    protected $casts = [
        'communityPosts' => 'array',
        'reviews' => 'array'
    ];

    /**
     * Returns stages that school affords
     * @return \App\Models\SchoolStage
     */
    public function stages()
    {
        return $this->hasMany(SchoolStage::class);
    }

    /**
     * Returns certificates that school afford
     * @return \App\Models\SchoolCertificate
     */
    public function certificates()
    {
        return $this->hasMany(SchoolCertificate::class);
    }

    /**
     * Returns local Images url of school
     * @return \App\Models\SchoolImage
     */
    public function images()
    {
        return $this->hasMany(SchoolImage::class);
    }

    /**
     * Returns the facilities that school have
     * @return \App\Models\SchoolFacility
     */
    public function facilities()
    {
        return $this->hasMany(SchoolFacility::class);
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
