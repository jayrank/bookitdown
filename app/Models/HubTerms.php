<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class HubTerms extends Authenticatable
{
	protected $connection = 'hubDB';
	
	protected $table = 'terms_and_conditions';
	
	protected $fillable = [
		'user_id',
		'name',
		'terms_and_conditions',
		'created_datetime',
		'updated_datetime'
	];
	
	public $timestamps = false;
}
