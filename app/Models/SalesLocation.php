<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesLocation extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'location';
	
	protected $fillable = [
		'unique_id',
		'deal_id',
		'business_name',
		'client_name',
		'mobile_number',
		'email',
		'street_address',
		'city',
		'postal_code',
		'price',
		'price_with_tax',
		'location_type',
		'lat',
		'longi',
		'created_at',
		'updated_at'
	];
	
	public $timestamps = false;
}
