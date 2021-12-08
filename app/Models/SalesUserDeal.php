<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesUserDeal extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'user_deals';
	
	protected $fillable = [
		'user_id',
		'deal_id'
	];
	
	public $timestamps = false;
}
