<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\School;

class CommunityPost extends Model
{  use Notifiable;
    protected $fillable = [
            'CommunityPost_Content',
            "user_id",
            "school_id",
            'CommunityPostImages',
            'num_of_likes',

    ];
    protected $casts = [
        'CommunityPostImages' => 'array'
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
