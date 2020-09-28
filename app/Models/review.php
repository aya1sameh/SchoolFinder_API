<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\School;
class Review extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'school_id', 
        'Review_description',
        'rating',
        "id",
        'created_at',
        'updated_at',
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
