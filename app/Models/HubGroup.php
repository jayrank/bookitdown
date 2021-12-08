<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class HubGroup extends Authenticatable
{
	protected $connection = 'hubDB';
	
	protected $table = 'groups';
	
	protected $fillable = [
		'user_id',
		'group_type',
		'location',
		'group_name',
		'keyword',
		'ifmember_message',
		'system_message',
		'auto_message',
		'sms_type',
		'bithday_enable',
		'active',
		'notify_signup',
		'double_optin'
	];
	
	public $timestamps = false;
}
