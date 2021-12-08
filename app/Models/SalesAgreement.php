<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesAgreement extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'agreement';
	
	protected $guarded = [];
	
	public $timestamps = false;
}
