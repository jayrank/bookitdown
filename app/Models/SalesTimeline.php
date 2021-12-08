<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesTimeline extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'timeline';
	
	protected $fillable = [
		'activity',
		'module',
		'deal_id',
		'user_id',
		'created',
		'pipeline_id'
	];
	
	public $timestamps = false;
}
