<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
            'CommunityPost_Content',
            "user_id",
            "school_id",
            'CommunityPostImages',

    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function schools()
    {
        return $this->belongsTo(School::class);
    }
    public function comments(){
        return $this->hasMany('App\Models\CommentOnPost');
    }
    public function likes(){
        return $this->hasMany('App\Models\LikeOnPost');
    }
   
    
}
