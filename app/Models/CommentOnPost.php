<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['user ID','post ID','content'];
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function communityPosts()
    {
        return $this->belongsTo('App\Models\CommunityPost');
    }
}
