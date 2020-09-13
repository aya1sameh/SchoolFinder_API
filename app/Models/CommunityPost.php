<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
    'CommunityPost_Content',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
