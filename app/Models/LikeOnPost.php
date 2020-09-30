<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeOnPost extends Model
{
    protected $fillable=['user_id','post_id'];
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function communityPosts()
    {
        return $this->belongsTo('App\Models\CommunityPost');
    }
}
