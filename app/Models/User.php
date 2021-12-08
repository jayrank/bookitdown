<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
		'last_name', 
        'email',
        'profile_pic',
        'country_code',
        'phone_number',
        'password',
		'business_type', 
		'country', 
		'timezone', 
		'currency', 
		'language',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	public static function getUserbyID($id = null){
		$User = User::select('id','first_name','last_name','email','stripe_customer_code')->where('id', $id)->first();
		return $User; 
	}

    

}
