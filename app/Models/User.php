<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verify_token','email_verified_at',
        'role','avatar','phone_no','address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verify_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'favorites' => 'array'
    ];

    public function favoriteSchoolList()
    {
        return $this->hasOne('App\Models\FavoriteSchoolsList');
    }

    public function test()
    {
        $role = $this->role;
        if($role == 'app_admin' || $role == 'admin')
            return 'special relations and functions for app admins';
        else if($role == 'school_admin')
            return 'special relations and functions for school admins';
        else
            return 'special relations and functions for school finder clients';   
    }

    //this user role is any but NOT app admin role
    public function question_posts(){
        //return $this->hasMany(Qpost::class);
    }

    //this user role is app admin role
    public function announcment_posts(){
        //return $this->hasMany(Apost::class);
    }

    public function comments(){
        //return $this->hasMany(Comment::class);
    }

    public function reviews(){
        //return $this->hasMany(Review::class);
    }

    public function notifications(){
        //return $this->hasMany(Notification::class);
    }

    //there are two types of user_id here:
    //1.written by user->school finder client role
    //2.send to user->school admin role
    public function messages(){
        //return $this->hasMany(Message::class);
    }

}
