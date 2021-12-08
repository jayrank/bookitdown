<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class HubLocation extends Authenticatable
{
	protected $connection = 'hubDB';
	
	protected $table = 'locations';
	
	protected $fillable = [
		'unique_id',
		'user_id',
		'location_name',
		'location_address',
		'locationlat',
		'locationlng',
		'phone',
		'email_address',
		'create_date'
	];
	
	public $timestamps = false;
}
