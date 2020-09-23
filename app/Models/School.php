<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SchoolCertificate;
use App\Models\SchoolStage;
use App\Models\SchoolImage;
use App\Models\SchoolFacility;
use App\Models\User;
use App\Models\Review;

class School extends Model
{
    protected $fillable =['name', 'gender','language','address','phone_number','description','fees','establishing_year','is_approved'];

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

    /**
     * Returns the admin of the school
     * @return \App\Models\SchoolFacility
     */
    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the admin of the school
     * @return \App\Models\SchoolFacility
     */
    public function checkIfUserisAdmin($id)
    {
        if($this->admin==NULL || $this->admin->id != $id)
                return false;

        return true;
    }
    
    /**
     * Returns the reviews of the school
     * @return \App\Models\Review
     */
    public function reviews()
    {
        /*TODO::not working*/
        return $this->hasMany(Review::class);
    }

    /**
     * Adjusts the overall rating of the school when a new rating is added
     * @return null
     */
    public function calculateOverAllRating()
    {
        $reviews=Review::where('school_id',$this->id)->get();
        $sum=0;
        $number=0;
        foreach($reviews as $review)
        {
            $sum+=$review->rating;
            $number++;
        }
        
        $avgRating=$sum/$number;
        $this->rating=$avgRating;
        $this->save();
    }

    
    /**
     * Returns the facilities that school have
     * @param $operation: a string which is either '+' or '-'
     * @return null
     */
    public function changeRatedBy($operation)
    {
        $ratedBy=$this->rated_by;
        if($operation=='+')
            $ratedBy++;
        else if($operation=='-')
            $ratedBy--;

        $this->rated_by=$ratedBy;
        $this->save();
    }

    //Function that returns number of users who like the school
    public function numberOfLikes()
    {
        //
    }
    
}
