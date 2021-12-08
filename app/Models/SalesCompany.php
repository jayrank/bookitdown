<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;

class SalesCompany extends Authenticatable
{
	protected $connection = 'salesDB';
	
	protected $table = 'company';
	
	protected $fillable = [
		'name',
		'contact_person_name',
		'phone',
		'phone_country_code',
		'email',
		'address',
		'billing_address',
		'street_name',
		'street_no',
		'city',
		'country',
		'state',
		'zip_code',
		'providence',
		'lat',
		'log',
		'status',
		'created',
		'modified'
	];
	
	public $timestamps = false;
}
