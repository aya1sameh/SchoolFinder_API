<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SchoolFinderClient extends Authenticatable
{
    //
    public $fillable = [
        "user_id",
        "fav_schools",
    ];
}

