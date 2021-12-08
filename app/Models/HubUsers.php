<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class HubUsers extends Authenticatable
{
	protected $connection = 'hubDB';
	
	protected $table = 'users';
	
	protected $fillable = [
		'first_name',
		'username',
		'email',
		'phone',
		'company_name',
		'password',
		'register',
		'user_country',
		'timezone',
		'api_type',
		'total_loc',
		'location',
		'is_email_verified',
		'is_otp_verified',
		'active',
		'package',
		'deal_id',
		'created',
		'agreement_id'
	];
	
	public $timestamps = false;
}
