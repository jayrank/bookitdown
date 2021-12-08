<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesClient extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'clients';
	
	protected $fillable = [
		'first_name',
		'username',
		'email',
		'phone',
		'company_name',
		'password',
		'user_country',
		'is_sendshipment',
		'is_hub_access',
		'is_wifi_access',
		'is_menu_access',
		'is_signage_access',
		'billing_type',
		'payment_currency',
		'created',
		'email_alerts',
		'email_alert_options',
		'register',
		'timezone',
		'api_type',
		'total_loc',
		'location',
		'is_email_verified',
		'is_otp_verified',
		'active',
		'is_payment_verified',
		'sd_user_id',
		'deal_id',
		'agreement_id'
	];
	
	public $timestamps = false;
}
