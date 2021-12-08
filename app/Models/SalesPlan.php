<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesPlan extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'plans';
	
	protected $fillable = [
		'plan_title',
		'plan_amount',
		'deal_id',
		'agreement_id',
		'plan_type',
		'created_date',
		'updated_date'
	];
	
	public $timestamps = false;
}
