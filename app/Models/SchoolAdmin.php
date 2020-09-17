<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SchoolAdmin extends Authenticatable
{
    //
    public $fillable = [
        "user_id",
        "school_id",
        "position_in_school",
    ];
}
