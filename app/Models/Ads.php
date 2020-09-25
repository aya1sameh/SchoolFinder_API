<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $fillable = [
        'user_id', 'ad_content','ad_image_url',
    ];

    public function admin()
    {
        return $this->belongsTo('App\Models\User');
    }
}
