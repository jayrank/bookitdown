<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fuser_id',
        'user_id',
        'firstname',
        'lastname',
		'mo_country_code',
		'mobileno', 
		'tel_country_code',
		'telephoneno', 
		'email', 
		'send_notification_by', 
		'preferred_language', 
		'accept_marketing_notification',
		'gender',
		'referral_source',
		'birthdate',
		'client_notes',
		'display_on_all_bookings',
		'address',
		'suburb',
		'city',
		'state',
		'zipcode',
        'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
	
	public static function getClientbyID($id = null){
		$Clients = Clients::select('*')->where('id', $id)->first();
		return $Clients; 
	}
}
