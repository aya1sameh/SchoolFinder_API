<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
            'CommunityPost_Content',
            "id",
            "user_id",
            "school_id",
        
            "created_at",
            "updated_at",
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function schools()
    {
        return $this->belongsTo(School::class);
    }
    
}
