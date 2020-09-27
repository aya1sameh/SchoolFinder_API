<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikesOfReview extends Model
{
    
    protected $fillable=['user_id','review_id'];
     public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function reviews()
    {
        return $this->belongsTo('App\Models\review');
    }
}
