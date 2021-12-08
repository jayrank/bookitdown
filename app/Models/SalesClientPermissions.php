<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesClientPermissions extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'clients_permissions';
	
	protected $fillable = [
		'plan_id',
		'client_id',
		'created_at',
		'updated_at'
	];
	
	public $timestamps = false;
}
