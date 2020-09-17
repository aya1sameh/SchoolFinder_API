<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SchoolFacility extends Model
{
    protected $fillable=['type','description','number','school_id'];
}
