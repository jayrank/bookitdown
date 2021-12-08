<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesDeal extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'deal';
	
	protected $fillable = [
		'name',
		'email',
		'country_code',
		'phone',
		'timezone',
		'bussiness_country_code',
		'business_phone',
		'business_name',
		'personal_email',
		'user_id',
		'stage_id',
		'pipeline_id',
		'group_id',
		'is_send_funnel',
		'last_notification_sent_on',
		'timezone',
		'company_id',
		'is_company',
		'created',
		'modified'
	];
	
	public $timestamps = false;
}
