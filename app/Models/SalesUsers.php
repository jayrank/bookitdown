<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesUsers extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'users';
	
	protected $fillable = [
		'first_name',
		'lead_percentage',
		'lead_current_weight',
		'lead_count',
		'active'
	];
	
	public $timestamps = false;
}
