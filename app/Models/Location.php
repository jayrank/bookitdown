<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $table="locations";
    protected $fillable = [
        'id',
        'user_id',
        'location_name',
        'country_code',
		'location_phone',
		'location_email', 
		'location_image',
        'no_business_address',
        'location_address',
        'location_latitude',
        'location_longitude',
        'loc_address',
        'loc_apt',
		'loc_district',
		'loc_city', 
		'loc_region',
        'loc_county',
        'loc_postcode',
        'loc_country',
		'is_same_billing_addr',
		'billing_company_name',
		'billing_address', 
		'billing_apt',
        'billing_city',
        'billing_region',
        'billing_postcode',
        'billing_notes',
        'location_description',
        'available_for',
		'position',
        'is_online',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
	
	public static function getLocationByID($id = null){
		$Location = Location::select('*')->where('id', $id)->first();
		return $Location; 
	}
}
