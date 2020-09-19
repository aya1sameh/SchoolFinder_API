<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable=['user ID','post ID','liked'];
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function communityPosts()
    {
        return $this->belongsTo('App\Models\CommunityPost');
    }
}
